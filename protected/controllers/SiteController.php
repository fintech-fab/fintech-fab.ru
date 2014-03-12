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
		Yii::app()->clientForm->resetSteps();
		$this->redirect(Yii::app()->createUrl("/form"));
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
		// номер активной вкладки, по умолчанию - первая
		$iActiveTab = 1;

		$oModel = new ContactForm;
		$aPost = Yii::app()->request->getPost('ContactForm');

		$iSite = 1;
		if (SiteParams::getIsIvanovoSite()) {
			$iSite = 2;
		}

		$aGroups = FaqGroup::getSiteGroups($iSite);
		$sViewQuestions = (empty($aGroups)) ? 'no_questions' : 'all_questions';
		$sTableQuestions = $this->renderPartial($sViewQuestions, array('model' => $aGroups), true);

		if (isset($aPost)) {
			// изменяем номер активной вкладки на 2 - с формой отправки
			$iActiveTab = 2;

			$oModel->setAttributes($aPost);
			if ($oModel->validate()) {
				$sEmail = SiteParams::getContactEmail();
				$sSubject = Dictionaries::C_FAQ_SUBJECT_SENT . ". " . Dictionaries::$aSubjectsQuestions[$oModel->subject];
				$sMessage =
					"Имя: " . $oModel->name . "\r\n" .
					"Телефон: " . $oModel->phone . "\r\n" .
					"E-Mail: " . $oModel->email . "\r\n" .
					"\r\n" .
					"Вопрос: \r\n" .
					$oModel->body;

				EmailComponent::sendEmail($sEmail, $sSubject, $sMessage, SiteParams::getEmailFrom());

				Yii::app()->user->setFlash('contact', Dictionaries::C_FAQ_SUCCESS);
			}
		}

		$sForm = $this->renderPartial('contact_us', array('model' => $oModel), true);

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

	public function actionEmail($code)
	{
		$oAdminKreddyApi = Yii::app()->adminKreddyApi;

		$sPostCode = trim($code);
		if (!empty($sPostCode)) {
			Yii::app()->session['code'] = $sPostCode;
			$this->redirect('/site/email');
		}
		if (!empty(Yii::app()->session['code'])) {
			$aRequest = array(
				'emailCode' => Yii::app()->session['code'],
			);
			Yii::app()->session['code'] = null;

			$aResponse = $oAdminKreddyApi->sendInfoFromEmail($aRequest);

			$aLastCode = $oAdminKreddyApi->getLastCode();
			if ($aLastCode == $oAdminKreddyApi::ERROR_NONE) {
				$this->render('email', array('sRequestType' => $aResponse['sEmailRequestType']));
			} else {
				$this->render('email', array('sRequestType' => 'codeError'));
			}
		} else {
			$this->redirect(Yii::app()->homeUrl);
		}
	}
}
