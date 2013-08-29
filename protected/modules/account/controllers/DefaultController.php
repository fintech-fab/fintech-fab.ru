<?php

class DefaultController extends Controller
{
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
				'actions' => array('logout', 'index', 'ajaxsendsms', 'checksmspass', 'ajaxindex'),
				'users'   => array('@'),
			),
			array(
				'deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$oApi = new AdminKreddyApi();
		$aData = $oApi->getClientData();
		$smsState = array('sent' => Yii::app()->session['smsPassSent'], 'passOK' => Yii::app()->session['smsPassOK']);
		$oSmsPassForm = new SMSPasswordForm();

		if ($aData && $aData['code'] === 0) {
			$aSecureData = $oApi->getClientSecureData();
			$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'smsState' => $smsState), true);
			$this->render('index', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm, 'data' => $aData, 'secureData' => $aSecureData));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	public function actionAjaxIndex()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$oApi = new AdminKreddyApi();
			$aData = $oApi->getClientData();
			$smsState = array('sent' => Yii::app()->session['smsPassSent'], 'passOK' => Yii::app()->session['smsPassOK']);
			$oSmsPassForm = new SMSPasswordForm();

			if ($aData && $aData['code'] === 0) {
				$aSecureData = $oApi->getClientSecureData();
				$sPassFormRender = $this->renderPartial('sms_password', array('passForm' => $oSmsPassForm, 'smsState' => $smsState), true);
				$this->renderPartial('index', array('passFormRender' => $sPassFormRender, 'passForm' => $oSmsPassForm, 'data' => $aData, 'secureData' => $aSecureData));
			} else {
				Yii::app()->user->logout();
				$this->redirect(Yii::app()->user->loginUrl);
			}
		}
	}

	public function actionAjaxSendSms()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$oApi = new AdminKreddyApi();
			$aResult = $oApi->sendSMS();

			if ($aResult['code'] == 0) {
				Yii::app()->session['smsPassSent'] = true;
			}

			echo CJSON::encode(array(
				"type" => $aResult['code'],
				"text" => $aResult['message'],
			));
		}
		Yii::app()->end();
	}

	public function actionCheckSMSPass()
	{
		//$SmsTries = SiteParams::MAX_SMSCODE_TRIES;

		if (Yii::app()->request->isAjaxRequest) {
			if (isset($_POST['SMSPasswordForm'])) {
				//$aAction = Yii::app()->clientForm->checkSmsCode($_POST['ClientConfirmPhoneViaSMSForm']);
				$passForm = new SMSPasswordForm();
				$aPostData = $_POST['SMSPasswordForm'];
				$passForm->setAttributes($aPostData);

				if ($passForm->validate()) {
					$oApi = new AdminKreddyApi();
					$sRes = $oApi->getSmsAuth($passForm->smsPassword);
					if ($sRes) {
						Yii::app()->session['smsPassOK'] = true;
						echo CJSON::encode(array(
							"type" => 0,
							"text" => Yii::app()->createUrl("account/ajaxindex"),
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

		if (Yii::app()->user->isGuest) {
			$model = new LoginForm;

			// if it is ajax validation request
			if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if (isset($_POST['LoginForm'])) {
				$model->attributes = $_POST['LoginForm'];
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
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}