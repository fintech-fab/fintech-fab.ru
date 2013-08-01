<?php

class SiteController extends Controller
{
	public $showTopPageWidget=true;

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		Yii::app()->session['done_steps']=0;
		Yii::app()->session['current_step']=0;

		$oForm = new ClientSelectProductForm();
		$this->render('../form/clientselectproduct',array('oClientCreateForm'=>$oForm));

	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    /**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	private function compareDataInCookie($cookieName,$attributeName,$checkValue)
	{
		$dataInCookie = false;
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку

			$aDecrypt= @unserialize($sDecrypt);
			if($aDecrypt&&($checkValue == $aDecrypt[$attributeName]))
			{
				$dataInCookie=true;
			}
		}
		return $dataInCookie;
	}

	private function getDataFromCookie($cookieName)
	{
		if(isset(Yii::app()->request->cookies[$cookieName]))
		{
			$cookie = Yii::app()->request->cookies[$cookieName];

			$sDecrypt=CryptArray::decryptVal($cookie);//декриптим куку
			$aDecrypt= @unserialize($sDecrypt);
			return $aDecrypt;
		}
		return false;
	}

	private function saveDataToCookie($cookieName,$data)
	{
		$sEncrypt = serialize($data);
		$cookieData = CryptArray::encryptVal($sEncrypt);

		$cookie = new CHttpCookie($cookieName, $cookieData);
		$cookie->expire = time()+60*60*2;
		Yii::app()->request->cookies[$cookieName] = $cookie;
	}
}
