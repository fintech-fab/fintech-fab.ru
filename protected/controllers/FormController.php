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

		$client_id = $this->getClientId();

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
						//Yii::app()->clientForm->nextStep('sms'); TODO
					}
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
			&& Yii::app()->session[get_class($oClientForm) . '_client_id'] == $client_id
		) {
			if (isset($oClientForm) && $oClientForm) {
				$sessionClientData = Yii::app()->session[get_class($oClientForm)];
				$oClientForm->setAttributes($sessionClientData);
			}
		}

		/**
		 * Рендер представления
		 */
		$sView = Yii::app()->clientForm->getView(); //запрашиваем имя текущего представления

		$aParams = array();
		if($sView=='client_confirm_phone_via_sms')
		{
			if(!Yii::app()->session['ClientConfirmPhoneViaSMSForm']){
				Yii::app()->session['ClientConfirmPhoneViaSMSForm']=array();
			}

			$aClientConfirmPhoneViaSMSFormSession = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
			if(!array_key_exists('iCountTries',$aClientConfirmPhoneViaSMSFormSession)){
				$aClientConfirmPhoneViaSMSFormSession['iCountTries'] = 0;
				Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $aClientConfirmPhoneViaSMSFormSession;
			}

			$aParams['tries']=Yii::app()->session['ClientConfirmPhoneViaSMSForm']['iCountTries'];
			$aParams['sms_sent']=(array_key_exists('sms_sent',$aClientConfirmPhoneViaSMSFormSession))?1:0;
		}

		$this->render($sView, array('oClientCreateForm' => $oClientForm)+$aParams);
	}

	/**
	 *  Переход на шаг $step
	 * @param int $step
	 */
	public function actionStep($step)
	{
		if ($step > 0) {
			if (Yii::app()->session['done_steps'] < ($step - 1)) {
				Yii::app()->session['current_step'] = Yii::app()->session['done_steps'];
			} else {
				Yii::app()->session['done_steps'] = $step - 1;
				Yii::app()->session['current_step'] = $step - 1;
			}
		}
		$this->redirect(Yii::app()->createUrl("form"));
	}


	/**
	 * Загрузка фото
	 */

	public function actionIdentification()
	{
		$client_id = $this->getClientId();

		if (Yii::app()->session['current_step'] == 7) {

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

		$client_id = $this->getClientId();

		if (Yii::app()->session['current_step'] == 8) {

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

	public function actionConfirmPhoneViaSms()
	{

		return;
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


	public function actionStart()
	{
		Yii::app()->clientForm->startNewForm();
		$this->redirect(Yii::app()->createUrl("form"));
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
		// если ajax-данные
		if(Yii::app()->request->isAjaxRequest){

			$client_id = Yii::app()->session['client_id'];

			$aClientConfirmPhoneViaSMSFormSession = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
			if(!array_key_exists('sms_sent',$aClientConfirmPhoneViaSMSFormSession)){
				$aClientConfirmPhoneViaSMSFormSession['sms_sent'] = true;
				Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $aClientConfirmPhoneViaSMSFormSession;
			}

			$aClientForm=ClientData::getClientDataById($client_id);

			ClientData::saveClientDataById($aClientForm, $client_id);
			// проверяем - есть ли уже код в базе.
			$bIsCodeAlreadySent = (bool)($aClientForm['sms_code']&&$aClientForm['sms_code']!='');

			// если да - новый не генерируем и выходим
			if($bIsCodeAlreadySent) {
				echo CHtml::encode("Ошибка: SMS уже отправлена");
				Yii::app()->end();
			}

			$aClientForm['sms_code']=$this->generateSMSCode(SiteParams::C_SMSCODE_LENGTH);

			// запись кода в базу
			ClientData::saveClientDataById($aClientForm, $client_id);

			Yii::app()->end();
		}
	}

	public function actionSendCode()
	{
		$client_id = Yii::app()->session['client_id'];

		// данные не ajax
		if(isset($_POST['ClientConfirmPhoneViaSMSForm']))
		{
			// если с данного ip уже просили SMS в течение 10 минут...
			if(1==2)
			{
				// бан1
			}

			// если с данного ip уже просили SMS 3 раза в течение 1 часа...
			if(1==2)
			{
				// бан2
			}

			$oClientConfirmPhoneViaSMSForm=new ClientConfirmPhoneViaSMSForm();

			$oClientConfirmPhoneViaSMSForm->setAttributes($_POST['ClientConfirmPhoneViaSMSForm']);

			if(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['iCountTries'] < SiteParams::MAX_ENTER_SMSCODE_TRIES){

				// проверить, что данные присланные и данные из базы по этому телефону совпадают
				if(ClientData::compareSMSCodeByClientId($client_id,$oClientConfirmPhoneViaSMSForm->sms_code)){

					//успешное подтверждение
					$aData['flag_sms_confirmed']=1;
					ClientData::saveClientDataById($aData,$client_id);

					$this->redirect(Yii::app()->createUrl('pages/view/formsent'));
				}

				// неуспешное подтверждение
				$aClientConfirmPhoneViaSMSFormSession = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
				$aClientConfirmPhoneViaSMSFormSession['iCountTries']++;
				Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $aClientConfirmPhoneViaSMSFormSession;

				$oClientForm = Yii::app()->clientForm->getFormModel();

				$this->render('client_confirm_phone_via_sms',array(
					'oClientCreateForm' => $oClientForm,
					'tries'=>Yii::app()->session['ClientConfirmPhoneViaSMSForm']['iCountTries'],
					'sms_sent'=>((array_key_exists('sms_sent',$aClientConfirmPhoneViaSMSFormSession))?1:0),
				));

			}

			// превышено число попыток
		}
	}

	private function getClientId()
	{
		return Yii::app()->session['client_id'];
	}

	private function clearSession()
	{
		Yii::app()->session['current_step']=0;
		Yii::app()->session['done_steps']=0;

		/*
		Yii::app()->session['ClientSelectProductForm']=null;
		Yii::app()->session['ClientSelectGetWayForm']=null;
		Yii::app()->session['ClientPersonalDataForm']=null;
		Yii::app()->session['ClientAddressForm']=null;
		Yii::app()->session['ClientJobInfoForm']=null;
		Yii::app()->session['ClientSendForm']=null;
		*/
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
