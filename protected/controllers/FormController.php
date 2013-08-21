<?php

class FormController extends Controller
{
	public $showTopPageWidget = true;

	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array                    $aPost
		 * @var string                   $sView
		 */

		$client_id = Yii::app()->clientForm->getClientId();

		if (empty($client_id)) {
			Yii::app()->session['tmp_client_id'] = Yii::app()->clientForm->getTmpClientId();
		}

		/*
		$cookie = new CHttpCookie('test', 'test');
		$cookie->expire = time()+60*60*24;
		Yii::app()->request->cookies['test'] = $cookie;
		*/

		/*
		 * Запрашиваем у компонента текущую форму (компонент сам определяет, какая форма соответствует
		 * текущему этапу заполнения анкеты)
		 */
		$oClientForm = Yii::app()->clientForm->getFormModel();

		/**
		 * AJAX валидация
		 */
		if (Yii::app()->clientForm->ajaxValidation()) //проверяем, не запрошена ли ajax-валидация
		{
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			echo IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		if ($aPost = Yii::app()->clientForm->getPostData()) //проверяем, был ли POST запрос
		{
			$oClientForm->attributes = $aPost; //передаем запрос в форму

			if (isset($oClientForm->go_identification)) {
				if ($oClientForm->validate()) {
					Yii::app()->clientForm->goIdentification($oClientForm->go_identification);
					$oClientForm = Yii::app()->clientForm->getFormModel();
				}
			} elseif ($oClientForm->validate()) {
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oClientForm = Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}

		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if (Cookie::compareDataInCookie('client', 'client_id', $client_id)
			&& Yii::app()->clientForm->getSessionFormClientId($oClientForm) == $client_id
		) {
			if (isset($oClientForm) && $oClientForm) {
				$sessionClientData = Yii::app()->clientForm->getSessionFormData($oClientForm);
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления

		if ($sView === 'form_sent') {
			Yii::app()->clientForm->setFormSent(false);
		}

		if ($sView == 'client_confirm_phone_via_sms') {
			$smsCountTries = Yii::app()->clientForm->getSmsCountTries();

			$flagExceededTries = ($smsCountTries >= SiteParams::MAX_SMSCODE_TRIES);
			$flagSmsSent = Yii::app()->clientForm->getFlagSmsSent();

			$this->render($sView, array(
				'oClientCreateForm' => $oClientForm,
				'phone'             => Yii::app()->clientForm->getSessionPhone(),
				'actionAnswer'      => ($flagExceededTries
					? Dictionaries::C_ERR_SMS_TRIES
					: ''),
				'flagExceededTries' => $flagExceededTries,
				'flagSmsSent'       => $flagSmsSent,
			));
		} else {
			$this->render($sView, array('oClientCreateForm' => $oClientForm));
		}
	}

	public function actionAjaxForm($form = null)
	{
		$this->layout = "//layouts/ajax";
		$oClientForm = new ClientPersonalDataForm();
		$sView = 'client_personal_data';
		// Just before rendering the view that
		// has our activeform
		Yii::app()->clientScript->corePackages = array();
		$this->renderPartial($sView, array('oClientCreateForm' => $oClientForm), false, true);
	}

	public function actionFullForm()
	{
		$oClientForm = new ClientFullForm();
		$this->render("full_form", array('oClientCreateForm' => $oClientForm));
	}

	/**
	 *  Переход на шаг $step
	 * @param int $step
	 */
	public function actionStep($step)
	{
		if ($step > 0) {
			$iDoneSteps = Yii::app()->clientForm->getDoneSteps();

			if ($iDoneSteps < ($step - 1)) {
				Yii::app()->clientForm->setCurrentStep($iDoneSteps);
			} else {
				Yii::app()->clientForm->setDoneSteps($step - 1);
				Yii::app()->clientForm->setCurrentStep($step - 1);
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}


	/**
	 * Загрузка фото
	 */
	public function actionIdentification()
	{
		if (Yii::app()->clientForm->getCurrentStep() == 3) {
			if (Yii::app()->clientForm->checkTmpIdentificationFiles("photo")) {
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 * Загрузка документов
	 */
	public function actionDocuments()
	{
		if (Yii::app()->clientForm->getCurrentStep() == 4) {

			if (Yii::app()->clientForm->checkTmpIdentificationFiles("documents")) {

				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}


	public function actionCheckWebCam()
	{
		$this->renderPartial('check_webcam', array(), false, true);
	}

	public function actionAjaxSendSms()
	{
		if (Yii::app()->request->isAjaxRequest) {
			echo Yii::app()->clientForm->ajaxSendSmsRequest();
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

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
