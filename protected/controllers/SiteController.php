<?php

/**
 * Class SiteController
 */
class SiteController extends Controller
{
	public $showTopPageWidget = true;

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class'     => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'    => array(
				'class' => 'CViewAction',
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

		Yii::app()->clientForm->setDoneSteps(0);
		Yii::app()->clientForm->setCurrentStep(0);


		if ($bFullForm = SiteParams::B_FULL_FORM) {
			$oClientForm = new ClientSelectProductForm2();
			$this->render('../form/client_select_product2', array('oClientCreateForm' => $oClientForm));
		} else {
			$oClientForm = new ClientSelectProductForm();
			$this->render('../form/client_select_product', array('oClientCreateForm' => $oClientForm));
		}
	}

	/**
	 * Вывод в JSON массива - города и регионы
	 *
	 * @param string $sInput
	 */
	public function actionGetCitiesAndRegionsListJson($sInput = "")
	{
		$aCitiesAndRegions = CitiesRegions::getAllCitiesAndRegions($sInput);
		echo CJSON::encode($aCitiesAndRegions);
	}

	/**
	 * Записывает в куку id города пользователя, переданный по post
	 */
	public function actionSetCityToCookie()
	{
		// берём имя города из post-запроса
		$sCityName = Yii::app()->request->getParam("cityName");
		$sCityAndRegion = Yii::app()->request->getParam("cityAndRegion");

		if (!Yii::app()->request->isPostRequest || empty($iId)) {
			//$this->redirect(Yii::app()->getHomeUrl());
		}

		// время жизни ставим - 30 суток
		$aCookieOptions = array(
			'expire' => time() + 60 * 60 * 24 * 30,
		);

		if (!empty($sCityName)) {
			// записываем в куку полученный id
			Yii::app()->request->cookies['cityName'] = new CHttpCookie("cityName", $sCityName, $aCookieOptions);
			Yii::app()->request->cookies['cityAndRegion'] = new CHttpCookie("cityAndRegion", $sCityAndRegion, $aCookieOptions);
			Yii::app()->request->cookies['citySelected'] = new CHttpCookie("citySelected", true, $aCookieOptions);
		}
		//обновляем виджет, свойство bUpdate указывает отдавать виджет для обновления, без лишних элементов
		$this->widget('UserCityWidget',array('bUpdate'=>true));
		Yii::app()->end();
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				$this->render('error', $error);
			}
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$oModel = new LoginForm;

		// if it is ajax validation request
		if (Yii::app()->request->isAjaxRequest) {
			echo CActiveForm::validate($oModel);
			Yii::app()->end();
		}

		$aPostData = Yii::app()->request->getPost('LoginForm', array());
		$oModel->setAttributes($aPostData);

		if (Yii::app()->request->isPostRequest && $oModel->validate() && $oModel->login()) {
			$this->redirect(Yii::app()->user->returnUrl);
		}

		// display the login form
		$this->render('login', array('model' => $oModel));
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
