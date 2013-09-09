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
				'actions' => array('login', 'resetpassword', 'ajaxsendsmscode', 'checksmscode'),
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

	public function actionIndex()
	{
		$oApi = new AdminKreddyApi();
		//получаем основную информацию из API
		$this->clientData = $oApi->getClientInfo();

		if ($oApi->getIsResultAuth($this->clientData)) {
			$this->smsState = $oApi->getSmsState($this->clientData);
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$oSmsPassForm = new SMSPasswordForm();
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
			if (Yii::app()->request->isAjaxRequest) {
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

	public function actionHistory()
	{
		$oApi = new AdminKreddyApi();
		//получаем основную информацию из API
		$this->clientData = $oApi->getClientInfo();
		//получаем историю операций из API
		$aHistory = $oApi->getHistory();

		$oHistoryDataProvider = $oApi->getHistoryDataProvider($aHistory);

		if ($oApi->getIsResultAuth($this->clientData)) {
			$this->smsState = $oApi->getSmsState($aHistory);
			/**
			 * Рендерим форму для запроса СМС-пароля, для последующего использования в представлении
			 */
			$oSmsPassForm = new SMSPasswordForm();
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true, false);
			if (Yii::app()->request->isAjaxRequest) {
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
	 * тест
	 */

	public function actionTest($getcode = 0)
	{
		$oApi = new AdminKreddyApi();
		$aTest = array('code' => $oApi::ERROR_AUTH);

		/**
		 * Действия на случай, если запрос пришел через AJAX
		 */
		if (Yii::app()->request->isAjaxRequest && isset($_POST['SMSCodeForm'])) {

			if (isset($_POST['SMSCodeForm'])) {
				$codeForm = new SMSCodeForm();
				$aPostData = $_POST['SMSCodeForm'];
				$codeForm->setAttributes($aPostData);
				$oApi = new AdminKreddyApi();
				if ($codeForm->validate()) {
					$aTest = $oApi->doTest(false, $codeForm->smsCode);
					echo $this->getCheckSmsCodeAnswer($aTest, 'test');
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
			if ($aTest['sms_status'] == 1) {
				echo CJSON::encode(array(
					"type" => 0,
					"text" => 'СМС с кодом отправлено',
				));
			} else {
				echo CJSON::encode(array(
					"type" => 3,
					"text" => 'Ошибка отправки СМС',
				));
			}
			Yii::app()->end();
		}
		/**
		 * Основные действия
		 */
		$this->clientData = $oApi->getClientInfo();
		$aTest = $oApi->doTest();

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
			//TODO needSmsActionCode проверять в модели
			$this->smsState = $oApi->getSmsState($aTest, $needSmsActionCode);
			$oSmsPassForm = new SMSPasswordForm();
			$oSmsCodeForm = new SMSCodeForm();
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'test'), true, false);
			$sCodeFormRender = $this->renderPartial('sms_action_code', array('codeForm' => $oSmsCodeForm, 'act' => 'test'), true, false);

			if (Yii::app()->request->isAjaxRequest) {
				$this->layout = '/layouts/column2_ajax';
				$this->renderWithoutProcess('test', array('passFormRender' => $sPassFormRender, 'codeFormRender' => $sCodeFormRender, 'aTest' => $aTest));
			} else {
				$this->render('test', array('passFormRender' => $sPassFormRender, 'codeFormRender' => $sCodeFormRender, 'aTest' => $aTest));
			}
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	/**
	 * Запрос на отправку SMS с паролем (для доступа к приватным данным в личном кабинете)
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

			//TODO: resend сделать. время на стороне клиента.
			if ($bResend &&
				!empty(Yii::app()->session['smsPassSentTime']) &&
				((time() - Yii::app()->session['smsPassSentTime']) < SiteParams::API_MINUTES_UNTIL_RESEND * 60)
			) {
				echo CJSON::encode(array(
					"type" => 2,
					"text" => SiteParams::API_MINUTES_RESEND_ERROR,
				));

				Yii::app()->end();
			}

			$oApi = new AdminKreddyApi();
			$aResult = $oApi->sendSMS($bResend);

			//TODO протестить
			if ($aResult && $aResult['code'] == 0 && $aResult['sms_status'] == 1) {
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
	 * Проверка SMS-пароля в личном кабинете (доступ к приватным данным)
	 *
	 * @param string $act
	 */

	public
	function actionCheckSmsPass($act = 'index')
	{
		if (Yii::app()->request->isAjaxRequest) {
			$aAnswer = array(
				"type" => 2,
				"text" => 'Неверный пароль!',
			);

			$bIsPost = Yii::app()->request->getIsPostRequest();
			$aPasswordForm = Yii::app()->request->getParam('SMSPasswordForm');

			if ($bIsPost && isset($aPasswordForm)) {
				$passForm = new SMSPasswordForm();
				$aPostData = $_POST['SMSPasswordForm'];
				$passForm->setAttributes($aPostData);
				$oApi = new AdminKreddyApi();
				if ($passForm->validate()) {
					$aResult = $oApi->getSmsAuth($passForm->smsPassword);
					if ($aResult['sms_status'] == $oApi::SMS_AUTH_OK) {
						$aAnswer = array(
							"type" => 0,
							"text" => Yii::app()->createUrl("account/" . $act),
						);
					} elseif ($aResult['sms_status'] == 5) { //превышено число попыток ввода пароля
						$aAnswer = array(
							"type" => 2,
							"text" => 'Вы превысили допустимое число попыток ввода пароля!',
						);
					}
				}
			}
			echo CJSON::encode($aAnswer);
		} else {
			$this->redirect(Yii::app()->createUrl("account"));
		}
	}

	/**
	 *  Форма восстановления пароля, необходимого для входа в личный кабинет
	 */
	public
	function actionResetPassword()
	{
		$this->layout = '/layouts/column1';

		if (Yii::app()->user->isGuest) {
			$model = new AccountResetPasswordForm;

			$curTime = time();
			$leftTime = (!empty(Yii::app()->session['smsCodeSentTime'])) ? Yii::app()->session['smsCodeSentTime'] : $curTime;
			$leftTime = $curTime - $leftTime;
			$leftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $leftTime;
			Yii::app()->session['smsCodeLeftTime'] = $leftTime;

			$this->render('reset_password', array('model' => $model, 'phoneEntered' => !empty(Yii::app()->session['smsCodeSentTime'])));
		} else {
			$this->redirect(Yii::app()->createUrl("/account"));
		}
	}

	/**
	 * Отправка на телефон SMS с кодом (для дальнейшей идентификации)
	 * Если SMS отсылается впервые, дополнительно проводится проверка телефона на валидность
	 *
	 * @param int $resend - повторная ли отправка SMS с кодом
	 */
	public
	function actionAjaxSendSmsCode($resend = 0)
	{
		if (Yii::app()->request->isAjaxRequest) {
			$bResend = ($resend == 1);

			$phone = "";

			if (!$bResend) {
				if (isset($_POST['AccountResetPasswordForm'])) {
					$model = new AccountResetPasswordForm('phoneRequired');
					$model->attributes = $_POST['AccountResetPasswordForm'];
					if (!$model->validate()) {
						echo CJSON::encode(array(
							"type" => 2,
							"text" => "Введите правильный телефон",
						));
						Yii::app()->end();
					} else {
						$phone = $model->phone;

						if (!empty(Yii::app()->session['smsCodeSent'])) {
							echo CJSON::encode(array(
								"type" => 2,
								"text" => "SMS уже отправлено",
							));

							Yii::app()->end();
						}
					}
				}
			} else {
				$phone = Yii::app()->session['phoneResetPassword'];
			}

			$curTime = time();
			$leftTime = (!empty(Yii::app()->session['smsCodeSentTime'])) ? Yii::app()->session['smsCodeSentTime'] : $curTime;
			$leftTime = $curTime - $leftTime;
			$leftTime = SiteParams::API_MINUTES_UNTIL_RESEND * 60 - $leftTime;

			if ($bResend &&
				($leftTime > 0)
			) {
				// обновляем оставшееся время
				Yii::app()->session['smsCodeLeftTime'] = $leftTime;
				echo CJSON::encode(array(
					"type" => 2,
					"text" => SiteParams::API_MINUTES_RESEND_ERROR,
				));

				Yii::app()->end();
			}

			$oApi = new AdminKreddyApi();
			$aResult = $oApi->resetPasswordSendSms($phone, $bResend);
			Yii::trace(CJSON::encode($aResult));

			if ($aResult && $aResult['code'] == 10 && $aResult['sms_status'] == 1) {
				Yii::app()->session['smsCodeSent'] = true;
				Yii::app()->session['smsCodeSentTime'] = time();
				Yii::app()->session['smsCodeLeftTime'] = SiteParams::API_MINUTES_UNTIL_RESEND * 60;
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
				$aResult['sms_message'] = 'Произошла неизвестная ошибка. Обратитесь на горячую линию.';
			}

			echo CJSON::encode(array(
				"type"     => $iSmsCode,
				"text"     => $aResult['sms_message'],
				"leftTime" => Yii::app()->session['smsCodeLeftTime'],
			));
		}
		Yii::app()->end();
	}

	/**
	 * Проверка кода, отправленного в SMS. Если код верен - отправка SMS с паролем на телефон из сессии
	 */
	public
	function actionCheckSmsCode()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$aAnswer = array(
				"type" => 2,
				"text" => 'Неверный код!',
			);

			// если в сессии нет телефона либо если пароль уже отправлен
			if (!empty(Yii::app()->session['phoneResetPassword']) && empty(Yii::app()->session['smsAuthDone'])) {
				$codeForm = new AccountResetPasswordForm('codeRequired');
				$aPostData = $_POST['AccountResetPasswordForm'];
				$codeForm->setAttributes($aPostData);
				$codeForm->phone = Yii::app()->session['phoneResetPassword'];
				$oApi = new AdminKreddyApi();
				if ($codeForm->validate()) {
					$aResult = $oApi->resetPasswordCheckSms($codeForm->phone, $codeForm->smsCode);
					if ($aResult['sms_status'] == $oApi::SMS_AUTH_OK) {
						Yii::app()->session['smsAuthDone'] = true;
						$aAnswer = array(
							"type" => 0,
							"text" => 'SMS с паролем отправлено',
						);
					} else {
						if ($aResult['sms_status'] == 5) { //превышено число попыток ввода пароля
							$aAnswer = array(
								"type" => 2,
								"text" => 'Вы превысили допустимое число попыток ввода пароля!',
							);
						}
					}
				}
			} else {
				$aAnswer = array(
					"type" => 2,
					"text" => 'Неизвестная ошибка! Перезагрузите страницу.',
				);
			}
			echo CJSON::encode($aAnswer);
		} else {
			$this->redirect(Yii::app()->createUrl("account"));
		}
		Yii::app()->end();
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
	 * @param $aResult
	 * @param $act
	 *
	 * @return string
	 */

	private
	function getCheckSmsCodeAnswer($aResult, $act)
	{
		if (AdminKreddyApi::checkSmsAuthStatus($aResult)) {
			$aRet = array(
				"type" => 0,
				"text" => Yii::app()->createUrl("account/" . $act, array('ajax' => 1)),
			);
		} elseif ($aResult['sms_status'] == 5) { //TODO превышено число попыток ввода пароля
			$aRet = array(
				"type" => 2,
				"text" => 'Вы превысили допустимое число попыток ввода пароля!',
			);
		} else {
			$aRet = array(
				"type" => 2,
				"text" => 'Неверный пароль!',
			);
		}

		return $aRet;
	}

}
