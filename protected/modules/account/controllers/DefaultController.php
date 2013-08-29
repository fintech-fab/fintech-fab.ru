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
				'actions' => array('logout', 'index'),
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
		$smsState = array();

		if ($aData && $aData['code'] === 0) {
			$aSecureData = $oApi->getClientSecureData();
			$this->render('index', array('data' => $aData, 'secureData' => $aSecureData, 'smsState' => $smsState));
		} else {
			Yii::app()->user->logout();
			$this->redirect(Yii::app()->user->loginUrl);
		}
	}

	public function actionAjaxSendSms()
	{
		if (Yii::app()->request->isAjaxRequest) {
			$oApi = new AdminKreddyApi();
			//$aResult = $oApi->s
		}
		Yii::app()->end();
	}

	public function actionCheckSMSCode()
	{
		if (isset($_POST['ClientConfirmPhoneViaSMSForm'])) {
			$aAction = Yii::app()->clientForm->checkSmsCode($_POST['ClientConfirmPhoneViaSMSForm']);
			if (isset($aAction['action'])) {
				switch ($aAction['action']) {
					case 'render':
						$this->render($aAction['params']['view'], $aAction['params']['params']);
						break;
					case 'redirect':
						$this->redirect($aAction['url']);
						break;
					default:
						$this->redirect(Yii::app()->createUrl("form"));
						break;
				}
			} else {
				$this->redirect(Yii::app()->createUrl("form"));
			}
		} else {
			$this->redirect(Yii::app()->createUrl("form"));
		}
	}

	public function actionLogin()
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
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}