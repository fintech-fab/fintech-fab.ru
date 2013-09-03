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
		echo '<pre>' . "";
		CVarDumper::dump(Yii::app()->session['akApi_token']);
		echo '</pre>';
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$oSmsPassForm = new SMSPasswordForm();

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9 || $this->clientData['code'] === 777)) {
			$this->smsState = array('sent' => Yii::app()->session['smsPassSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $this->clientData['code'] == 9);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
			$this->render('index', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm));
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
		if (Yii::app()->request->isAjaxRequest) {
			$oApi = new AdminKreddyApi();
			$this->clientData = $oApi->getClientInfo();
			$oSmsPassForm = new SMSPasswordForm();

			if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9 || $this->clientData['code'] === 777)) {
				$this->smsState = array('sent' => Yii::app()->session['smsPassSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $this->clientData['code'] == 9);
				$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'index'), true);
				$this->renderPartial('index', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm));
			} else {
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->user->loginUrl);
			}
		}
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

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			$this->smsState = array('sent' => Yii::app()->session['smsPassSent'], 'smsAuthDone' => Yii::app()->session['smsAuthDone'], 'needSmsPass' => $this->clientData['code'] == 9);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true);
			$this->render('history', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm, 'history' => $aHistory));
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
		$oApi = new AdminKreddyApi();
		$this->clientData = $oApi->getClientInfo();
		$aHistory = $oApi->getHistory();
		$oSmsPassForm = new SMSPasswordForm();

		if ($this->clientData && ($this->clientData['code'] === 0 || $this->clientData['code'] === 9)) {
			$this->smsState = array('sent' => Yii::app()->session['smsPassSent'], 'smsAuthDone' => $this->clientData['code'] == 0, 'needSmsPass' => $this->clientData['code'] == 9);
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'act' => 'history'), true);
			$this->renderPartial('history', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm, 'history' => $aHistory));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}


	/**
	 * Запрос на отправку SMS с паролем
	 */

	public function actionAjaxSendSms()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$oApi = new AdminKreddyApi();
			$aResult = $oApi->sendSMS();

			if ($aResult && $aResult['code'] == 0 || $aResult['sms_auth' == 1]) {
				Yii::app()->session['smsPassSent'] = true;
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
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}