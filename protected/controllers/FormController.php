<?php

class FormController extends Controller
{
	public $showTopPageWidget = true;

	public function actionIndex()
	{
		/**
		 * @var ClientCreateFormAbstract $oClientForm
		 * @var array $aPost
		 * @var string $sView
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
			echo IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			Yii::app()->end();
		}

		/**
		 * Обработка POST запроса
		 */

		if ($aPost = Yii::app()->clientForm->getPostData()) //проверяем, был ли POST запрос
		{
			$oClientForm->attributes = $aPost; //передаем запрос в форму

			if(isset($oClientForm->go_identification))
			{
				if($oClientForm->validate()){
					if($oClientForm->go_identification==1)
					{
						Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
					}
					elseif($oClientForm->go_identification==2)
					{
						Yii::app()->clientForm->nextStep(3);
					}
					$oClientForm = Yii::app()->clientForm->getFormModel();
				}
			}
			elseif ($oClientForm->validate()){
				Yii::app()->clientForm->formDataProcess($oClientForm);
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
				$oClientForm = Yii::app()->clientForm->getFormModel(); //заново запрашиваем модель (т.к. шаг изменился)
			}

		}

		/**
		 * Загрузка данных из сессии в форму, если данные существуют и client_id сессии совпадает с оным в куке
		 */

		if (Cookie::compareDataInCookie('client', 'client_id', $client_id)
			//TODO переделать обращение к сессии
			&& Yii::app()->clientForm->getSessionFormClientId($oClientForm) == $client_id
		) {
			if (isset($oClientForm) && $oClientForm) {
				//TODO переделать обращение к сессии
				$sessionClientData = Yii::app()->clientForm->getSessionFormData($oClientForm);
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления

		if($sView=='client_confirm_phone_via_sms')
		{
			$smsCountTries = Yii::app()->clientForm->getSmsCountTries();

			$flagExceededTries = ($smsCountTries >= SiteParams::MAX_SMSCODE_TRIES);
			$flagSmsSent = Yii::app()->clientForm->getFlagSmsSent();

			$this->render($sView, array(
				'oClientCreateForm' => $oClientForm,
				'phone' => Yii::app()->clientForm->getSessionPhone(),
				'actionAnswer' => ($flagExceededTries
					?Dictionaries::C_ERR_SMS_TRIES
					:''),
				'flagExceededTries' => $flagExceededTries,
				'flagSmsSent' => $flagSmsSent,
			));
		}else{
			$this->render($sView, array('oClientCreateForm' => $oClientForm));
		}
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
	//TODO в компонент
	public function actionIdentification()
	{
		$client_id = Yii::app()->clientForm->getClientId();

		if (Yii::app()->clientForm->getCurrentStep() == 7) {

			$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $client_id . '/';

			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PHOTO . '.png';

			if ($this->checkFiles($aFiles)) {
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
			$this->actionIndex();
		} else $this->actionIndex();
	}

	/**
	 * Загрузка документов
	 */
	//TODO в компонент
	public function actionDocuments()
	{

		$client_id = Yii::app()->clientForm->getClientId();

		if (Yii::app()->clientForm->getCurrentStep() == 8) {

			$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $client_id . '/';

			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_LAST . '.png';



			if ($this->checkFiles($aFiles)) {
				$aClientData['flag_identified']=1;
				if(isset($client_id)) {
					ClientData::saveClientDataById($aClientData,$client_id);
				}//TODO протестить
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}

	//TODO в компонент
	private function checkFiles($aFiles)
	{
		if(!isset($aFiles)||gettype($aFiles)!='array') return false;

		foreach ($aFiles as $sFile) {
			if (!file_exists($sFile) || !getimagesize($sFile)) {
				return false;
			}
		}
		return true;
	}

	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	public function actionAjaxSendSms()
	{
		if(Yii::app()->request->isAjaxRequest){
			echo Yii::app()->clientForm->ajaxSendSmsRequest();
		}
		Yii::app()->end();
	}

	public function actionCheckSMSCode()
	{
		if(isset($_POST['ClientConfirmPhoneViaSMSForm']))
		{
			$aAction=Yii::app()->clientForm->checkSmsCode();

			if (isset($aAction['action'])) {
				if ($aAction['action'] === 'render') {
					$this->render($aAction['params']['view'], $aAction['params']['params']);
				} elseif ($aAction['action'] === 'redirect') {
					$this->redirect($aAction['url']);
				}
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
