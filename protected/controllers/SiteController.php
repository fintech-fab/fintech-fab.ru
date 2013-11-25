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
		Yii::app()->clientForm->setDoneSteps(0);
		Yii::app()->clientForm->setCurrentStep(0);


		if (SiteParams::getIsIvanovoSite()) {
			$oClientForm = new ClientFlexibleProductForm();
			$this->render('../form/client_flexible_product', array('oClientCreateForm' => $oClientForm));
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
	 * Записывает в куку город и регион пользователя, переданный по post
	 * Отдает AJAX-запросы перерисованный виджет
	 */
	public function actionSetCityToCookie()
	{
		// берём имя города из post-запроса
		$sCityName = CHtml::encode(Yii::app()->request->getParam("cityName"));
		//берем имя города и регион из post-запроса
		$sCityAndRegion = CHtml::encode(Yii::app()->request->getParam("cityAndRegion"));

		if (!Yii::app()->request->isPostRequest || empty($sCityName) || empty($sCityAndRegion)) {
			Yii::app()->end();
		}

		// время жизни ставим - 30 суток
		$aCookieOptions = Yii::app()->session->getCookieParams();
		if (!empty($aCookieOptions['domain'])) {
			$aCookieOptions = array(
				'expire' => time() + 60 * 60 * 24 * 30,
				'domain' => $aCookieOptions['domain']
			);
		} else {
			$aCookieOptions = array(
				'expire' => time() + 60 * 60 * 24 * 30,
			);
		}

		if (!empty($sCityName)) {
			// записываем в куки полученные данные
			Yii::app()->request->cookies['cityName'] = new CHttpCookie("cityName", $sCityName, $aCookieOptions);
			Yii::app()->request->cookies['cityAndRegion'] = new CHttpCookie("cityAndRegion", $sCityAndRegion, $aCookieOptions);
			Yii::app()->request->cookies['citySelected'] = new CHttpCookie("citySelected", true, $aCookieOptions);
		}
		//обновляем виджет, свойство bUpdate указывает отдавать виджет для обновления, без лишних элементов
		$this->widget('UserCityWidget', array('bUpdate' => true));
		Yii::app()->end();
	}

	public function actionGetFooterLinks()
	{
		$this->widget('FooterLinksWidget');
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
	 *
	 */
	public function actionContact()
	{
		$this->render('contact');
	}

	public function actionFaq()
	{
		$model = new ContactForm;
		$aPost = Yii::app()->request->getParam('ContactForm');

		// номер активной вкладки, по умолчанию - первая
		$iActiveTab = 1;

		if (isset($aPost)) {
			// изменяем номер активной вкладки на 2 - с формой отправки
			$iActiveTab = 2;

			$model->setAttributes($aPost);
			if ($model->validate()) {
				$sEmail = 'e.barsova@fintech-fab.ru'; //TODO: изменить operator@kreddy.ru
				$sSubject = Dictionaries::C_FAQ_SUBJECT_SENT . ". " . Dictionaries::$aSubjectsQuestions[(int)$model->subject];
				$sMessage =
					"Имя: " . $model->name . "\r\n" .
					"Телефон: " . $model->phone . "\r\n" .
					"E-Mail: " . $model->email . "\r\n" .
					"\r\n" .
					"Вопрос: \r\n" .
					$model->body;

				EmailComponent::sendEmail($sEmail, $sSubject, $sMessage);

				Yii::app()->user->setFlash('contact', Dictionaries::C_FAQ_SUCCESS);
			}
		}

		$aGroups = FaqGroup::model()->with('questions')->findAll();
		$sTableQuestions = $this->renderPartial('all_questions', array('model' => $aGroups), true);
		$sForm = $this->renderPartial('contact_us', array('model' => $model), true);

		$this->render('faq', array('sForm' => $sForm, 'sTableQuestions' => $sTableQuestions, 'iActiveTab' => $iActiveTab));
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
