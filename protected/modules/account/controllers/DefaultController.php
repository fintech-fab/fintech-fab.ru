<?php
/**
 * Class DefaultController
 */
class DefaultController extends Controller
{

	public $layout = '/layouts/column2';

	public $clientData;
	public $smsState;

	/**
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('login'),
				'users'   => array('*'),
			),
			array(
				'allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('logout', 'index', 'ajaxsendsms', 'checksmspass', 'history', 'test'),
				'users'   => array('@'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Главная страница личного кабинета
	 */

	public function actionIndex($ajax = 0)
	{
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$oSmsPassForm = new SMSPasswordForm();

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($this->clientData['code'] === 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
			if ($ajax == 1) {
				$this->layout = '/layouts/column2_ajax';
				$this->renderWithoutProcess('index', array('passFormRender' => $sPassFormRender));
			} else {
				$this->render('index', array('passFormRender' => $sPassFormRender));
			}
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * История операций
	 */

	public function actionHistory($ajax = 0)
	{
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$aHistory = $oApi->getHistory();
		$oSmsPassForm = new SMSPasswordForm();

		$oHistoryDataProvider = $this->getHistoryDataProvider($aHistory);


		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($aHistory['code'] === 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true, false);
			if ($ajax == 1) {
				$this->layout = '/layouts/column2_ajax';
				$this->renderWithoutProcess('history', array('passFormRender' => $sPassFormRender, 'history' => $aHistory, 'historyProvider' => $oHistoryDataProvider));
			} else {
				$this->render('history', array('passFormRender' => $sPassFormRender, 'history' => $aHistory, 'historyProvider' => $oHistoryDataProvider));
			}
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}


	/**
	 * История операций
	 */

	public function actionTest($getcode = 0)
	{
		$oApi = new AdminKreddyApi();
		$aTest = array('code' => $oApi::ERROR_AUTH);
		//TODO тут и везде в прочих местах брать данные не из _POST, а через средства Yii
		if (Yii::app()->request->isAjaxRequest && isset($_POST['SMSCodeForm'])) {

			if (isset($_POST['SMSCodeForm'])) {
				$codeForm = new SMSCodeForm();
				$aPostData = $_POST['SMSCodeForm'];
				$codeForm->setAttributes($aPostData);
				$oApi = new AdminKreddyApi();
				if ($codeForm->validate()) {
					$aTest = $oApi->doTest(false, $codeForm->smsCode);
					echo $this->checkSmsCode($aTest, $oApi, 'test');
				} else {
					echo CJSON::encode(array(
						"type" => 2,
						"text" => 'Неизвестная ошибка!',
					));
				}
				Yii::app()->end();
			}
		} elseif (Yii::app()->request->isAjaxRequest && $getcode == 1) {
			$aTest = $oApi->doTest(true);
			echo '<pre>' . "";
			CVarDumper::dump($aTest);
			echo '</pre>';
			//if ($aTest['sms_auth'] == 1) {
			//}
		} else {
			$aTest = $oApi->doTest();
		}


		$this->clientData = $oApi->getClientInfo();
		$oSmsPassForm = new SMSPasswordForm();
		$oSmsCodeForm = new SMSCodeForm();


		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($aTest['code'] === 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			if ($aTest['code'] === 10) {
				$needSmsActionCode = true;
			} else {
				$needSmsActionCode = false;
			}
			$this->setSmsState($needSmsPass, $needSmsActionCode);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'test'), true, false);
			$sCodeFormRender = $this->renderPartial('sms_action_code', array('codeForm' => $oSmsCodeForm, 'act' => 'test'), true, false);
			$this->render('test', array('passFormRender' => $sPassFormRender, 'codeFormRender' => $sCodeFormRender, 'aTest' => $aTest));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * Запрос на отправку SMS с паролем
	 */

	public function actionAjaxSendSms($resend = 0)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$bResend = ($resend == 1);

			if (!$bResend && !empty(Yii::app()->session['smsPassSent'])) {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => "SMS уже отправлено",
				));

				Yii::app()->end();
			}

			//TODO: вынести в SiteParams число минут до следующей отправки SMS
			if ($bResend &&
				!empty(Yii::app()->session['smsPassSentTime']) &&
				((time() - Yii::app()->session['smsPassSentTime']) < 1 * 60)
			) {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => "Должна пройти минута до следующей отправки SMS",
				));

				Yii::app()->end();
			}

			$oApi = new AdminKreddyApi();
			$aResult = $oApi->sendSMS($bResend);

			if ($aResult && $aResult['code'] == 0 || $aResult['sms_status'] == 1) {
				Yii::app()->session['smsPassSent'] = true;
				Yii::app()->session['smsPassSentTime'] = time();
			}

			if (empty($aResult['sms_message'])) {
				$aResult['sms_message'] = '';
			}

			if (isset($aResult['sms_status'])) {
				switch ($aResult['sms_status']) {
					case 1:
						$iSmsCode = 0;
						break;
					default:
						$iSmsCode = 3;
						break;
				}
			} else {
				$iSmsCode = 3;
			}

			echo CJSON::encode(array(
				"type" => $iSmsCode,
				"text" => $aResult['sms_message'],
			));
		}
		Yii::app()->end();
	}

	/**
	 * Проверка SMS-пароля
	 *
	 * @param string $act
	 */

	public
	function actionCheckSmsPass($act = 'index')
	{
		if (Yii::app()->request->isAjaxRequest) {
			if (isset($_POST['SMSPasswordForm'])) {
				$passForm = new SMSPasswordForm();
				$aPostData = $_POST['SMSPasswordForm'];
				$passForm->setAttributes($aPostData);
				$oApi = new AdminKreddyApi();
				if ($passForm->validate()) {
					$aResult = $oApi->getSmsAuth($passForm->smsPassword);
					if ($aResult['sms_status'] == $oApi::SMS_AUTH_OK) {
						Yii::app()->session['smsAuthDone'] = true;
						echo CJSON::encode(array(
							"type" => 0,
							"text" => Yii::app()->createUrl("account/" . $act, array('ajax' => 1)),
						));
					} elseif ($aResult['sms_status'] == 5) { //превышено число попыток ввода пароля
						echo CJSON::encode(array(
							"type" => 2,
							"text" => 'Вы превысили допустимое число попыток ввода пароля!',
						));
					} else {
						echo CJSON::encode(array(
							"type" => 2,
							"text" => 'Неверный пароль!',
						));
					}

				} else {
					echo CJSON::encode(array(
						"type" => 2,
						"text" => 'Неверный пароль!',
					));
				}
			} else {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => 'Неизвестная ошибка!',
				));
			}
		} else {
			$this->redirect(Yii::app()->createUrl("account"));
		}
	}

	public
	function actionLogin()
	{
		$this->layout = '/layouts/column1';

		if (Yii::app()->user->isGuest) {
			$model = new AccountLoginForm;

			// if it is ajax validation request
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if (isset($_POST['AccountLoginForm'])) {
				$model->attributes = $_POST['AccountLoginForm'];
				// validate user input and redirect to the previous page if valid
				if ($model->validate() && $model->login()) {
					$this->redirect(Yii::app()->createUrl("/account"));
				}
			}
			// display the login form
			$this->render('login', array('model' => $model));
		} else {
			$this->redirect(Yii::app()->createUrl("/account"));
		}
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public
	function actionLogout()
	{
		Yii::app()->session['smsPassSent'] = false;
		Yii::app()->session['smsAuthDone'] = false;
		$oApi = new AdminKreddyApi();
		$oApi->logout();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * @param      $view
	 * @param null $data
	 * @param bool $return
	 *
	 * @return string
	 */

	public
	function renderWithoutProcess($view, $data = null, $return = false)
	{
		if ($this->beforeRender($view)) {
			$output = $this->renderPartial($view, $data, true);
			if (($layoutFile = $this->getLayoutFile($this->layout)) !== false) {
				$output = $this->renderFile($layoutFile, array('content' => $output), true);
			}

			$this->afterRender($view, $output);

			if ($return) {
				return $output;
			} else {
				echo $output;
			}
		}

		return false;
	}

	/**
	 * @param bool $needSmsPass
	 * @param bool $needSmsActionCode
	 */

	private
	function setSmsState($needSmsPass = false, $needSmsActionCode = false)
	{
		$this->smsState = array('passSent' => Yii::app()->session['smsPassSent'], 'codeSent' => Yii::app()->session['smsCodeSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $needSmsPass, 'needSmsActionCode' => $needSmsActionCode);
	}

	/**
	 * @return CSort
	 */
	private
	function getHistorySort()
	{
		$sort = new CSort;
		$sort->defaultOrder = 'time DESC';
		$sort->attributes = array('time', 'type', 'type_id', 'amount');

		return $sort;
	}

	/**
	 * @param $aHistory
	 *
	 * @return \CArrayDataProvider
	 */

	private
	function getHistoryDataProvider($aHistory)
	{
		if (isset($aHistory) && $aHistory['code'] === 0 && isset($aHistory['history'])) {
			$oHistoryDataProvider = new CArrayDataProvider($aHistory['history'], array('keyField' => 'time', 'sort' => $this->getHistorySort()));
		} else {
			$oHistoryDataProvider = new CArrayDataProvider(array());
		}

		return $oHistoryDataProvider;
	}

	/**
	 * @param $aResult
	 * @param $oApi
	 * @param $act
	 *
	 * @return string
	 */
	public
	function checkSmsCode($aResult, $oApi, $act)
	{
		if ($aResult['sms_status'] == $oApi::SMS_AUTH_OK) {
			Yii::app()->session['smsAuthDone'] = true;

			return CJSON::encode(array(
				"type" => 0,
				"text" => Yii::app()->createUrl("account/" . $act, array('ajax' => 1)),
			));
		} elseif ($aResult['sms_status'] == 5) { //превышено число попыток ввода пароля
			return CJSON::encode(array(
				"type" => 2,
				"text" => 'Вы превысили допустимое число попыток ввода пароля!',
			));
		} else {
			return CJSON::encode(array(
				"type" => 2,
				"text" => 'Неверный пароль!',
			));
		}


	}


}
