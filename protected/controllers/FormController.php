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


			// если с данного ip нельзя запросить SMS, выдаём ошибку
			if( !Yii::app()->antiBot->checkSmsRequest() ){
				echo CHtml::encode(Dictionaries::C_ERR_GENERAL);
				Yii::app()->end();
			}

			$client_id = Yii::app()->clientForm->getClientId();
			$aClientForm=ClientData::getClientDataById($client_id);

			// проверяем - есть ли уже код в базе.
			if(!empty($aClientForm['sms_code'])) {

				// если есть - выдаём ошибку, что SMS уже отправлена
				echo CHtml::encode(Dictionaries::C_ERR_SMS_SENT);
				Yii::app()->end();
			}

			$aClientForm['sms_code']=$this->generateSMSCode(SiteParams::C_SMSCODE_LENGTH);

			// добавляем в лог запрос sms с этого ip
			//Yii::app()->antiBot->addSmsRequest();

			//TODO: добавить отправку SMS на номер

			//ClientData::saveClientDataById($aClientForm, $client_id);
			Yii::app()->end();
		}
	}

	public function actionCheckSMSCode()
	{
		if(isset($_POST['ClientConfirmPhoneViaSMSForm']))
		{
			$client_id = Yii::app()->clientForm->getClientId();
			$oClientSMSForm=new ClientConfirmPhoneViaSMSForm();
			$oClientSMSForm->setAttributes($_POST['ClientConfirmPhoneViaSMSForm']);

			$flagSmsSent = Yii::app()->clientForm->getFlagSmsSent();

			$smsCountTries = Yii::app()->clientForm->getSmsCountTries();

			if ($smsCountTries < SiteParams::MAX_SMSCODE_TRIES) {

				// проверить, что присланный код валиден и совпадает с кодом из базы
				if ($oClientSMSForm->validate()
					&& ClientData::compareSMSCodeByClientId($oClientSMSForm->sms_code, $client_id)
				) {
					// подтверждение по SMS выполнено успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
					$aData['flag_sms_confirmed'] = 1;
					ClientData::saveClientDataById($aData, $client_id);

					Yii::app()->clientForm->clearClientSession();

					$this->redirect(Yii::app()->createUrl('pages/view/formsent'));
				} else {

					$smsCountTries += 1;
					Yii::app()->clientForm->setSmsCountTries($smsCountTries);

					// если это была последняя попытка
					if($smsCountTries == SiteParams::MAX_SMSCODE_TRIES)
					{
						$actionAnswer = Dictionaries::C_ERR_SMS_TRIES;
						$flagExceededTries=true;
					}
					else
					{
						$triesLeft = SiteParams::MAX_SMSCODE_TRIES - $smsCountTries;
						$actionAnswer = Dictionaries::C_ERR_SMS_WRONG.' '.Dictionaries::C_ERR_TRIES_LEFT. $triesLeft;
						$flagExceededTries=false;
					}

					$oClientForm = Yii::app()->clientForm->getFormModel();
					$this->render('client_confirm_phone_via_sms', array(
						'oClientCreateForm' => $oClientForm,
						'phone'             => Yii::app()->clientForm->getSessionPhone(),
						'actionAnswer'      => $actionAnswer,
						'flagExceededTries' => $flagExceededTries,
						'flagSmsSent'       => $flagSmsSent,
					));
				}
			} else {

				$oClientForm = Yii::app()->clientForm->getFormModel();

				$this->render('client_confirm_phone_via_sms', array(
					'oClientCreateForm' => $oClientForm,
					'phone'             => Yii::app()->clientForm->getSessionPhone(),
					'actionAnswer'      => Dictionaries::C_ERR_SMS_TRIES,
					'flagExceededTries' => true,
					'flagSmsSent'       => $flagSmsSent,
				));
			}
		} else {
			$this->redirect(Yii::app()->createUrl("form"));
		}
	}

	/**
	 * @param $iLength
	 * @return string
	 */
	private function generateSMSCode($iLength = SiteParams::C_SMSCODE_LENGTH) {
		// генерация рандомного кода
		list($usec, $sec) = explode(' ', microtime());
		$fSeed = (float) $sec + ((float) $usec * 100000);

		mt_srand($fSeed);

		$sMin = "1"; $sMax = "9";
		for($i=0;$i<$iLength;++$i)
		{
			$sMin.="0"; $sMax.="9";
		}

		$sGeneratedCode = mt_rand((int)$sMin, (int)$sMax);
		$sGeneratedCode = substr($sGeneratedCode,1,$iLength);

		return $sGeneratedCode;
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
