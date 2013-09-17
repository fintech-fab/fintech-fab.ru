<?php

/**
 * Class FormController
 */
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
			$sEcho = IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			echo $sEcho;
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
					//$oClientForm = Yii::app()->clientForm->getFormModel();
					$this->redirect(Yii::app()->createUrl("form"));
				}
			} elseif ($oClientForm->validate()) {
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				//$oClientForm = Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
				$this->redirect(Yii::app()->createUrl("form"));
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

		//если текущее представление form_sent, то отключаем его отображение при следующей загрузке
		if ($sView === 'form_sent') {
			Yii::app()->clientForm->setFormSent(false);
		}

		$this->render($sView, array('oClientCreateForm' => $oClientForm));
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
		$bIdent = false;

		if (Yii::app()->clientForm->getCurrentStep() == 3 && !SiteParams::B_FULL_FORM) {
			$bIdent = true;
		} elseif (Yii::app()->clientForm->getCurrentStep() == 2 && SiteParams::B_FULL_FORM) {
			$bIdent = true;
		}

		if ($bIdent) {
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
		$bDocs = false;

		if (Yii::app()->clientForm->getCurrentStep() == 4 && !SiteParams::B_FULL_FORM) {
			$bDocs = true;
		} elseif (Yii::app()->clientForm->getCurrentStep() == 3 && SiteParams::B_FULL_FORM) {
			$bDocs = true;
		}

		if ($bDocs) {

			if (Yii::app()->clientForm->checkTmpIdentificationFiles("documents")) {

				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 * проверка веб-камеры
	 */
	public function actionCheckWebCam()
	{
		$this->renderPartial('check_webcam', array(), false, true);
	}

	/**
	 * Отправка SMS с кодом
	 */
	public function actionSendSmsCode()
	{
		// если в сессии телефона нет - редирект на form
		if (!Yii::app()->clientForm->getSessionPhone()) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		// отправляем SMS с кодом. если $oAnswer !== true, то ошибка
		$oAnswer = Yii::app()->clientForm->sendSmsCode();

		// если были ошибки при отправке, то выводим их в представлении
		if ($oAnswer !== true) {
			$this->render('confirm_phone_full_form2/send_sms_code_error', array(
					'sErrorMessage' => $oAnswer,
				)
			);
			Yii::app()->end();
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 * проверка кода, введённого пользователем
	 */
	public function actionCheckSmsCode()
	{
		// забираем данные из POST и заносим в форму ClientConfirmPhoneViaSMSForm
		$aPostData = Yii::app()->request->getParam('ClientConfirmPhoneViaSMSForm');

		// если не было POST запроса - перенаправляем на form
		if (empty($aPostData)) {
			$this->redirect(Yii::app()->createUrl("form"));
		}

		// сверяем код. если $oAnswer !== true, то ошибка
		$oAnswer = Yii::app()->clientForm->checkSmsCode($aPostData);

		// если был POST запрос и код неверен - добавляем текст ошибки к атрибуту
		if ($oAnswer !== true) {
			$oClientSmsForm = new ClientConfirmPhoneViaSMSForm();
			$oClientSmsForm->setAttributes($aPostData);
			$oClientSmsForm->addError('sms_code', $oAnswer);
			$this->render('confirm_phone_full_form2/check_sms_code', array(
				'oClientCreateForm' => $oClientSmsForm,
			));
			Yii::app()->end();
		}

		$this->redirect(Yii::app()->createUrl("form"));
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
