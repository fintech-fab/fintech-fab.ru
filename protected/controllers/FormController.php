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

			if ($oClientForm->validate()){
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
			$aClientConfirmPhoneViaSMSFormSession = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
			if(!array_key_exists('iCountTries',$aClientConfirmPhoneViaSMSFormSession)){
				$aClientConfirmPhoneViaSMSFormSession['iCountTries'] = 0;
				Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $aClientConfirmPhoneViaSMSFormSession;
			}
			$aParams['tries']=Yii::app()->session['ClientConfirmPhoneViaSMSForm']['iCountTries'];
		}

		$this->render($sView, array('oClientCreateForm' => $oClientForm)+$aParams);
	}

	/**
	 *  Переход на шаг $step
	 * @param int $step
	 */
	public function actionStep($step)
	{
		if ($step !== 0) {
			if (Yii::app()->session['done_steps'] < ($step - 1)) {
				Yii::app()->session['current_step'] = Yii::app()->session['done_steps'];
			} else {
				Yii::app()->session['done_steps'] = $step - 1;
				Yii::app()->session['current_step'] = $step - 1;
			}
			$this->redirect(Yii::app()->createUrl("form"));
		}

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
			$aClientForm=ClientData::getClientDataById($client_id);

			ClientData::saveClientDataById($aClientForm, $client_id);
			// проверяем - есть ли уже код в базе.
			$bIsCodeAlreadySent = (bool)($aClientForm['sms_code']&&$aClientForm['sms_code']!='');

			// если да - новый не генерируем и выходим
			if($bIsCodeAlreadySent) {
				Yii::app()->end();
			}

			// генерация рандомного кода
			mt_srand($this->make_seed());
			$sGeneratedCode = mt_rand(1000000,9999999);
			$sGeneratedCode = substr($sGeneratedCode,1,6);

			$aClientForm['sms_code']=$sGeneratedCode;

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
			$oClientConfirmPhoneViaSMSForm=new ClientConfirmPhoneViaSMSForm();

			$oClientConfirmPhoneViaSMSForm->setAttributes($_POST['ClientConfirmPhoneViaSMSForm']);

			if(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['iCountTries'] < 10){

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
	 * генерация рандомного кода;
	 * сеет с микросекундами
	 * @return float
	 */
	private function make_seed() {
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
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
