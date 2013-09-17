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
	 * Отправка SMS с кодом
	 * TODO: исправить, что после очистки сессии можно зайти на страницу
	 */
	public function actionSendSmsCode()
	{
		// номера шагов формы проверки SMS кода
		if (SiteParams::B_FULL_FORM) {
			$iStepNext = 3;
		} else {
			$iStepNext = 7;
		}

		// если в сессии стоит флаг, что SMS отправлено
		if (Yii::app()->clientForm->getFlagSmsSent() && Yii::app()->clientForm->getSessionPhone()) {
			Yii::app()->clientForm->setCurrentStep($iStepNext);
		} elseif (!Yii::app()->clientForm->getSessionPhone()) {
			Yii::app()->clientForm->clearClientSession();
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
		} else {
			Yii::app()->clientForm->setCurrentStep($iStepNext);
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	/**
	 * проверка кода, введённого пользователем
	 */
	public function actionCheckSmsCode()
	{
		// номера шагов формы отправки SMS кода
		if (SiteParams::B_FULL_FORM) {
			$iStepBack = 2;
		} else {
			$iStepBack = 6;
		}

		// забираем данные из POST и заносим в форму ClientConfirmPhoneViaSMSForm
		$aPostData = Yii::app()->request->getParam('ClientConfirmPhoneViaSMSForm');

		//если СМС не отправлено, то редирект на /form, с установкой текущего шага $iStepBack
		if (!Yii::app()->clientForm->getFlagSmsSent() && Yii::app()->clientForm->getSessionPhone()) {
			Yii::app()->clientForm->setCurrentStep($iStepBack);
		} elseif (!Yii::app()->clientForm->getSessionPhone()) {
			//иначе если в сессии нет телефона, то стираем всю сессию и редирект на /form
			Yii::app()->clientForm->clearClientSession();
		}

		// сверяем код. если $oAnswer !== true, то ошибка
		$oAnswer = Yii::app()->clientForm->checkSmsCode($aPostData);

		// если код неверен и не превышено число ошибок - добавляем текст ошибки к атрибуту
		if (!empty($aPostData) && $oAnswer !== true) {

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
