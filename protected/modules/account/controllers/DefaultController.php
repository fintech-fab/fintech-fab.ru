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
				'actions' => array('logout', 'index', 'ajaxsendsms', 'checksmspass', 'ajaxindex', 'history', 'ajaxhistory'),
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

	public function actionIndex()
	{
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$oSmsPassForm = new SMSPasswordForm();

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($this->clientData['code'] == 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
			$this->render('index', array('passFormRender' => $sPassFormRender));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * AJAX-перезагрузка главной страницы
	 */
	public function actionAjaxIndex()
	{
		$this->layout = '/layouts/column2_ajax';
		//if (Yii::app()->request->isAjaxRequest) {
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$oSmsPassForm = new SMSPasswordForm();

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($this->clientData['code'] == 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
			$this->renderWithoutProcess('index', array('passFormRender' => $sPassFormRender));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
		//}
	}

	/**
	 * История операций
	 */

	public function actionHistory()
	{
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$aHistory = $oApi->getHistory();
		$oSmsPassForm = new SMSPasswordForm();

		$oHistoryDataProvider = $this->getHistoryDataProvider($aHistory);


		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($aHistory['code'] == 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true, false);
			$this->render('history', array('passFormRender' => $sPassFormRender, 'history' => $aHistory, 'historyProvider' => $oHistoryDataProvider));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * AJAX-перезагрузка истории
	 */

	public function actionAjaxHistory()
	{
		$this->layout = '/layouts/column2_ajax';
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$aHistory = $oApi->getHistory();
		$oSmsPassForm = new SMSPasswordForm();


		$oHistoryDataProvider = $this->getHistoryDataProvider($aHistory);

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			if ($aHistory['code'] == 9) {
				$needSmsPass = true;
			} else {
				$needSmsPass = false;
			}
			$this->setSmsState($needSmsPass);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true, false);
			$this->renderWithoutProcess('history', array('passFormRender' => $sPassFormRender, 'history' => $aHistory, 'historyProvider' => $oHistoryDataProvider));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * Запрос на отправку или переотправку SMS с паролем
	 */
	public function actionAjaxSendSms($resend = 0)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$bResend = ($resend == 1);

			if (!$bResend && !empty(Yii::app()->session['smsPassSent'])) {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => "", //TODO: текст ошибки?
				));

				Yii::app()->end();
			}

			if ($bResend &&
				!empty(Yii::app()->session['smsPassSentTime']) &&
				((time() - Yii::app()->session['smsPassSentTime']) < 1 * 60)
			) {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => "текст ошибки", //TODO: текст ошибки?
				));

				Yii::app()->end();
			}

			$oApi = new AdminKreddyApi();
			$aResult = $oApi->sendSMS($bResend);

			if ($aResult && $aResult['code'] == 0 || $aResult['sms_auth'] == 1) {
				Yii::app()->session['smsPassSent'] = true;
				Yii::app()->session['smsPassSentTime'] = time();
			}

			if (empty($aResult['sms_message'])) {
				$aResult['sms_message'] = '';
			}

			if (isset($aResult['sms_auth'])) {
				switch ($aResult['sms_auth']) {
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

	public function actionCheckSMSPass($act = 'index')
	{
		if (Yii::app()->request->isAjaxRequest) {
			if (isset($_POST['SMSPasswordForm'])) {
				$passForm = new SMSPasswordForm();
				$aPostData = $_POST['SMSPasswordForm'];
				$passForm->setAttributes($aPostData);

				if ($passForm->validate()) {
					$oApi = new AdminKreddyApi();
					$bRes = $oApi->getSmsAuth($passForm->smsPassword);
					if ($bRes) {
						Yii::app()->session['smsAuthDone'] = true;
						echo CJSON::encode(array(
							"type" => 0,
							"text" => Yii::app()->createUrl("account/ajax" . $act),
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

	public function actionLogin()
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
	public function actionLogout()
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

	public function renderWithoutProcess($view, $data = null, $return = false)
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
	}

	/**
	 * @param bool $needSmsPass
	 *
	 */

	private function setSmsState($needSmsPass = false)
	{
		$this->smsState = array('sent' => Yii::app()->session['smsPassSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $needSmsPass);
	}

	/**
	 * @return CSort
	 */
	private function getHistorySort()
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

	private function getHistoryDataProvider($aHistory)
	{
		if (isset($aHistory) && $aHistory['code'] === 0 && isset($aHistory['history'])) {
			$oHistoryDataProvider = new CArrayDataProvider($aHistory['history'], array('keyField' => 'time', 'sort' => $this->getHistorySort()));
		} else {
			$oHistoryDataProvider = new CArrayDataProvider(array());
		}

		return $oHistoryDataProvider;
	}

}
