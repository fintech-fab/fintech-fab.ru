<?php
/*
 * Компонент ClientForm
 * занимается обработкой данных сессии и cookies
 * и передачей результата по запросу контроллера форм.
 * Также выполняет команды контроллера по обработке форм.
 */

/**
 * Class ClientFormComponent
 */
class ClientFormComponent
{
	private $client_id;
	private $current_step;
	private $done_steps;

	public function init()
	{
		if (!$this->client_id = $this->getClientId()) {
			$this->client_id = false;
		}

		if (!$this->current_step = $this->getCurrentStep()) {
			$this->setCurrentStep(0);
		}

		if (!$this->done_steps = $this->getDoneSteps()) {
			$this->setDoneSteps(0);
		}
	}

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * НЕ выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function ajaxValidation()
	{
		$bIsAjax = Yii::app()->request->getIsAjaxRequest();
		$sAjaxClass = Yii::app()->request->getParam('ajax');

		return ($bIsAjax && $sAjaxClass === get_class($this->getFormModel()));
	}

	/**
	 * Функция занимается сохранением данных,
	 * полученных при ajax-валидации,
	 * в сессию, куки и БД
	 *
	 * @param ClientCreateFormAbstract $oClientForm
	 */
	public function saveAjaxData(ClientCreateFormAbstract $oClientForm)
	{

		$aValidFormData = $oClientForm->getValidAttributes();

		if (get_class($oClientForm) === 'ClientPersonalDataForm') {
			if (isset($aValidFormData['phone'])) {
				/**
				 * проверяем, есть ли в куках информация о клиенте
				 * и сравниваем введенный телефон с телефоном в куках.
				 * в случае успешности восстанавливаем client_id из куки.
				 * иначе создаем нового клиента и сохраняем информацию
				 * о нем в сессию и куку.
				 */

				$aCookieData = Cookie::getDataFromCookie('client');

				if (
					$aCookieData &&
					Cookie::compareDataInCookie('client', 'phone', $aValidFormData['phone']) &&
					!empty($aCookieData['client_id'])
				) {
					$this->client_id = $aCookieData['client_id'];
					$this->setClientId($this->client_id);

				} else {
					/**
					 * функция addClient()ищет клиента в базе по телефону,
					 * и если находит - возвращает запись с указанным телефоном как результат,
					 * либо создает новую запись
					 */
					$oClientData = ClientData::addClient($aValidFormData['phone']);
					Yii::app()->antiBot->addFormRequest();
					$this->setClientId($oClientData->client_id);
					$this->client_id = $oClientData->client_id;

					$aCookieData = array('client_id' => $oClientData->client_id, 'phone' => $aValidFormData['phone']);
					Cookie::saveDataToCookie('client', $aCookieData);
				}
			}

			if ($this->client_id) {
				$aValidFormData['product'] = $this->getSessionProduct();
				$aValidFormData['get_way'] = $this->getSessionGetWay();
				$aValidFormData['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aValidFormData['ip'] = Yii::app()->request->getUserHostAddress();
				ClientData::saveClientDataById($aValidFormData, $this->client_id);

			}
		} else {
			if ($this->client_id) {
				ClientData::saveClientDataById($aValidFormData, $this->client_id);
				$aValidFormData['client_id'] = $this->client_id;
			}
		}

		Yii::app()->session[get_class($oClientForm)] = $aValidFormData;
		Yii::app()->session[get_class($oClientForm) . '_client_id'] = $this->client_id;

		return;
	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract|ClientSelectProductForm|ClientSelectGetWayForm|ClientPersonalDataForm $oClientForm
	 *
	 */
	public function formDataProcess(ClientCreateFormAbstract $oClientForm)
	{
		if (get_class($oClientForm) === 'ClientPersonalDataForm') {

			/**
			 * проверяем, есть ли в куках информация о клиенте
			 * и сравниваем введенный телефон с телефоном в куках.
			 * в случае успешности восстанавливаем client_id из куки.
			 * иначе создаем нового клиента и сохраняем информацию
			 * о нем в сессию и куку.
			 */

			$aCookieData = Cookie::getDataFromCookie('client');

			if (
				$aCookieData &&
				Cookie::compareDataInCookie('client', 'phone', $oClientForm->phone) &&
				!empty($aCookieData['client_id'])
			) {
				$this->client_id = $aCookieData['client_id'];
				$this->setClientId($this->client_id);

			} else {
				/**
				 * функция addClient()ищет клиента в базе по телефону,
				 * и если находит - возвращает запись с указанным телефоном как результат
				 * либо создает новую запись
				 */

				$oClientData = ClientData::addClient($oClientForm);
				Yii::app()->antiBot->addFormRequest();
				$this->setClientId($oClientData->client_id);

				$this->client_id = $oClientData->client_id;

				$aCookieData = array('client_id' => $oClientData->client_id, 'phone' => $oClientData->phone);
				Cookie::saveDataToCookie('client', $aCookieData);
			}
			if ($this->client_id) {
				$aClientFormData = $oClientForm->getAttributes();
				$aClientFormData['product'] = $this->getSessionProduct();
				$aClientFormData['get_way'] = $this->getSessionGetWay();
				$aValidFormData['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aValidFormData['ip'] = Yii::app()->request->getUserHostAddress();
				ClientData::saveClientDataById($aClientFormData, $this->client_id);

				if (!$this->checkIdentificationFiles() || $this->checkTmpIdentificationFiles()) {
					if ($this->moveIdentificationFiles()) {
						$aClientData['flag_identified'] = 1;
						ClientData::saveClientDataById($aClientData, $this->client_id);
					}
				}


			}
		} else {
			if ($this->client_id) {
				$aClientFormData = $oClientForm->getAttributes();
				ClientData::saveClientDataById($aClientFormData, $this->client_id);
			}
		}
		/**
		 * Сохраняем данные формы в сессию
		 */
		$aClientFormData = $oClientForm->getAttributes();
		Yii::app()->session[get_class($oClientForm)] = $aClientFormData;
		Yii::app()->session[get_class($oClientForm) . '_client_id'] = $this->client_id;

		return;
	}

	/**
	 * Делает запрос на отправку SMS и возвращает ответ в json
	 *
	 * @return string
	 */
	public function ajaxSendSmsRequest()
	{
		// если с данного ip нельзя запросить SMS, выдаём ошибку
		if (!Yii::app()->antiBot->checkSmsRequest()) {
			return CJSON::encode(array(
				"type" => "2",
				"text" => Dictionaries::C_ERR_GENERAL,
			));
		}

		$iClientId = $this->getClientId();

		$aClientForm = ClientData::getClientDataById($iClientId);

		// проверяем - есть ли уже код в базе.
		if (!empty($aClientForm['sms_code'])) {
			return CJSON::encode(array(
				"type" => "1",
				"text" => Dictionaries::C_ERR_SMS_SENT,
			));
		}

		$sSmsCode = $this->getSmsCode();
		if(empty($sSmsCode))
		{
			$sSmsCode = $this->generateSMSCode(SiteParams::C_SMSCODE_LENGTH);
			$this->setSmsCode($sSmsCode);
		}
		$aClientForm['sms_code'] = $sSmsCode;

		$sPhone = $this->getSessionPhone();

		$sMessage = "Ваш код подтверждения: " . $sSmsCode;
		if (!empty($sPhone) && !empty($sSmsCode)) {
			//отправляем СМС
			SmsGateSender::getInstance()->send('7' . $sPhone, $sMessage);

			//если отправлено успешно,
			//то добавляем в лог запрос sms с этого ip
			Yii::app()->antiBot->addSmsRequest();
			$this->setFlagSmsSent(true);


			ClientData::saveClientDataById($aClientForm, $iClientId);

			return CJSON::encode(array(
				"type" => "0",
				"text" => Dictionaries::C_SMS_SUCCESS,

			));
		} else {
			return CJSON::encode(array(
				"type" => "3",
				"text" => Dictionaries::C_ERR_SMS_CANT_SEND,
			));
		}

	}

	/**
	 * Сверяет код из $aPostData с кодом из базы
	 *
	 * @param array $aPostData
	 *
	 * @return array
	 */
	public
	function checkSmsCode($aPostData)
	{
		$client_id = Yii::app()->clientForm->getClientId();
		$oClientSMSForm = new ClientConfirmPhoneViaSMSForm();
		$oClientSMSForm->setAttributes($aPostData);

		$flagSmsSent = Yii::app()->clientForm->getFlagSmsSent();

		$smsCountTries = Yii::app()->clientForm->getSmsCountTries();

		if ($smsCountTries < SiteParams::MAX_SMSCODE_TRIES) {
			if ($oClientSMSForm->validate()
				&& ClientData::compareSMSCodeByClientId($oClientSMSForm->sms_code, $client_id)
			) {
				// подтверждение по SMS выполнено успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
				$aData['flag_sms_confirmed'] = 1;
				ClientData::saveClientDataById($aData, $client_id);

				Yii::app()->clientForm->clearClientSession();

				//$this->redirect(Yii::app()->createUrl('pages/view/formsent'));
				return array(
					'action' => 'redirect',
					'url'    => Yii::app()->createUrl('pages/view/formsent'),
				);
			} else {
				$smsCountTries += 1;
				Yii::app()->clientForm->setSmsCountTries($smsCountTries);

				// если это была последняя попытка
				if ($smsCountTries == SiteParams::MAX_SMSCODE_TRIES) {
					$actionAnswer = Dictionaries::C_ERR_SMS_TRIES;
					$flagExceededTries = true;
				} else {
					$triesLeft = SiteParams::MAX_SMSCODE_TRIES - $smsCountTries;
					$actionAnswer = Dictionaries::C_ERR_SMS_WRONG . ' ' . Dictionaries::C_ERR_TRIES_LEFT . $triesLeft;
					$flagExceededTries = false;
				}

				$oClientForm = Yii::app()->clientForm->getFormModel();

				/*$this->render('client_confirm_phone_via_sms', array(
					'oClientCreateForm' => $oClientForm,
					'phone'             => Yii::app()->clientForm->getSessionPhone(),
					'actionAnswer'      => $actionAnswer,
					'flagExceededTries' => $flagExceededTries,
					'flagSmsSent'       => $flagSmsSent,
				));*/

				return array(
					'action' => 'render',
					'params' => array(
						'view'   => 'client_confirm_phone_via_sms',
						'params' => array(
							'oClientCreateForm' => $oClientForm,
							'phone'             => Yii::app()->clientForm->getSessionPhone(),
							'actionAnswer'      => $actionAnswer,
							'flagExceededTries' => $flagExceededTries,
							'flagSmsSent'       => $flagSmsSent,
						)
					)
				);

			}
		} else {

			$oClientForm = Yii::app()->clientForm->getFormModel();

			/*$this->render('client_confirm_phone_via_sms', array(
				'oClientCreateForm' => $oClientForm,
				'phone'             => Yii::app()->clientForm->getSessionPhone(),
				'actionAnswer'      => Dictionaries::C_ERR_SMS_TRIES,
				'flagExceededTries' => true,
				'flagSmsSent'       => $flagSmsSent,
			));*/

			return array(
				'action' => 'render',
				'params' => array(
					'view'   => 'client_confirm_phone_via_sms',
					'params' => array(
						'oClientCreateForm' => $oClientForm,
						'phone'             => Yii::app()->clientForm->getSessionPhone(),
						'actionAnswer'      => Dictionaries::C_ERR_SMS_TRIES,
						'flagExceededTries' => true,
						'flagSmsSent'       => $flagSmsSent,
					)
				)
			);
		}
	}

	/**
	 * Возвращает номер текущего шага (нумерация с нуля)
	 *
	 * @return int
	 */
	public
	function getCurrentStep()
	{
		$this->current_step = Yii::app()->session['current_step'];

		return $this->current_step;
	}

	/**
	 * @param $iStep
	 */
	public
	function setCurrentStep($iStep)
	{
		Yii::app()->session['current_step'] = $iStep;
		$this->current_step = $iStep;
	}

	/**
	 * Возвращает число пройденных шагов (нумерация с нуля)
	 *
	 * @return int
	 */
	public
	function getDoneSteps()
	{
		$this->done_steps = Yii::app()->session['done_steps'];

		return $this->done_steps;
	}

	/**
	 * @param int $iSteps
	 */
	public
	function setDoneSteps($iSteps)
	{
		Yii::app()->session['done_steps'] = $iSteps;
		$this->done_steps = $iSteps;
	}

	/**
	 * Возвращает модель текущей формы.
	 *
	 * @return ClientCreateFormAbstract
	 */

	public
	function getFormModel() //возвращает модель, соответствующую текущему шагу заполнения формы
	{
		switch ($this->current_step) {
			case 0:
				return new ClientSelectProductForm();
				break;
			case 1:
				return new ClientSelectGetWayForm();
				break;
			case 2:
			case 3:
			case 4:
				return new InviteToIdentificationForm();
				break;
			case 5:
				return new ClientPersonalDataForm();
				break;
			case 6:
				return new ClientAddressForm();
				break;
			case 7:
				return new ClientJobInfoForm();
				break;
			case 8:
				return new ClientSendForm();
				break;
			case 9:
				return new ClientConfirmPhoneViaSMSForm();
				break;
			default:
				return new ClientSelectProductForm();
				break;
		}

	}

	/**
	 * Возвращает название необходимого для генерации представления.
	 *
	 * @return string
	 */
	public
	function getView()
	{
		switch ($this->current_step) {
			case 0:
				return 'client_select_product';
				break;
			case 1:
				return 'client_select_get_way';
				break;
			case 2:
				return 'invite_to_identification';
				break;
			case 3:
				return 'identification';
				break;
			case 4:
				return 'documents';
				break;
			case 5:
				return 'client_personal_data';
				break;
			case 6:
				return 'client_address';
				break;
			case 7:
				return 'client_job_info';
				break;
			case 8:
				return 'client_send';
				break;
			case 9:
				return 'client_confirm_phone_via_sms';
				break;
			default:
				return 'client_select_product';
				break;
		}
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|null
	 */
	public
	function getPostData()
	{

		switch ($this->current_step) {
			case 0:
			{

				if (isset($_POST['ClientSelectProductForm'])) {
					return $_POST['ClientSelectProductForm'];
				}

				return null;
			}
				break;
			case 1:
			{
				if (isset($_POST['ClientSelectGetWayForm'])) {
					return $_POST['ClientSelectGetWayForm'];
				}

				return null;
			}
				break;
			case 2:
			{
				if (isset($_POST['InviteToIdentificationForm'])) {
					return $_POST['InviteToIdentificationForm'];
				}

				return null;
			}
				break;
			case 5:
			{
				if (isset($_POST['ClientPersonalDataForm'])) {
					return $_POST['ClientPersonalDataForm'];
				}

				return null;
			}
				break;
			case 6:
			{
				if (isset($_POST['ClientAddressForm'])) {
					return $_POST['ClientAddressForm'];
				}

				return null;
			}
				break;
			case 7:
			{
				if (isset($_POST['ClientJobInfoForm'])) {
					return $_POST['ClientJobInfoForm'];
				}

				return null;
			}
				break;
			case 8:
			{
				if (isset($_POST['ClientSendForm'])) {
					return $_POST['ClientSendForm'];
				}

				return null;
			}
				break;
			case 9:
			{
				if (isset($_POST['ClientConfirmPhoneViaSMSForm'])) {
					return $_POST['ClientConfirmPhoneViaSMSForm'];
				}

				return null;
			}
				break;
			default:
				return null;
				break;

		}
	}

	public
	function goIdentification($iIdentCode)
	{
		if (!empty($iIdentCode)) {
			if ($iIdentCode == 1) {
				Yii::app()->clientForm->nextStep(); //переводим анкету на следующий шаг
			} elseif ($iIdentCode == 2) {
				Yii::app()->clientForm->nextStep(3);
			}

			if ($iIdentCode == 1 || $iIdentCode == 2) {
				$aClientData['identification_type'] = $iIdentCode;
				ClientData::saveClientDataById($aClientData, $this->client_id);
			}
		}
	}

	/**
	 * Переводит обработку форм на следующий шаг
	 *
	 * @param int $iSteps
	 */
	public
	function nextStep($iSteps = 1)
	{
		if (!($iSteps <= 3)) {
			$iSteps = 1;
		}
		$this->current_step += $iSteps;
		Yii::app()->session['current_step'] = $this->current_step;
		if ($this->done_steps < Yii::app()->session['current_step']) {
			Yii::app()->session['done_steps'] = $this->done_steps = Yii::app()->session['current_step'];
		}
	}

	/**
	 * @return string
	 */
	public
	function getSessionPhone()
	{
		return (isset(Yii::app()->session['ClientPersonalDataForm']['phone'])) ? Yii::app()->session['ClientPersonalDataForm']['phone'] : '';
	}

	/**
	 * @return int
	 */
	public
	function getClientId()
	{
		return Yii::app()->session['client_id'];
	}

	/**
	 * @param $iClientId
	 */
	public
	function setClientId($iClientId)
	{
		Yii::app()->session['client_id'] = $iClientId;
	}

	/**
	 * @return int
	 */
	public
	function getTmpClientId()
	{
		if (empty(Yii::app()->session['tmp_client_id'])) {
			$tmp_client_id = 'tmp' . rand(0, 999999);
			Yii::app()->session['tmp_client_id'] = $tmp_client_id;
		} else {
			$tmp_client_id = Yii::app()->session['tmp_client_id'];
		}

		return $tmp_client_id;
	}

	/**
	 * @param ClientCreateFormAbstract $oClientForm
	 *
	 * @return int|null
	 */
	public
	function getSessionFormClientId(ClientCreateFormAbstract $oClientForm)
	{
		if (!isset($oClientForm)) {
			return null;
		}

		return Yii::app()->session[get_class($oClientForm) . '_client_id'];
	}

	/**
	 * @param ClientCreateFormAbstract $oClientForm
	 *
	 * @return null|array
	 */
	public
	function getSessionFormData(ClientCreateFormAbstract $oClientForm)
	{
		if (!isset($oClientForm)) {
			return null;
		}

		return Yii::app()->session[get_class($oClientForm)];
	}

	/**
	 * @return int номер выбранного продукта
	 */
	public
	function getSessionProduct()
	{
		return Yii::app()->session['ClientSelectProductForm']['product'];
	}

	/**
	 * @return int номер выбранного спссоба
	 */
	public
	function getSessionGetWay()
	{
		return Yii::app()->session['ClientSelectGetWayForm']['get_way'];
	}

	/**
	 * @return int
	 */
	public
	function getSmsCountTries()
	{
		return (isset(Yii::app()->session['smsCountTries'])) ? Yii::app()->session['smsCountTries'] : 0;
	}

	/**
	 * @param int $iSmsCountTries
	 */
	public
	function setSmsCountTries($iSmsCountTries)
	{
		Yii::app()->session['smsCountTries'] = $iSmsCountTries;
	}

	/**
	 * @return string
	 */

	public function getSmsCode()
	{
		return (isset(Yii::app()->session['smsCode'])) ? Yii::app()->session['smsCode'] : '';
	}

	/**
	 * @param $sSmsCode
	 */
	public
	function setSmsCode($sSmsCode)
	{
		Yii::app()->session['smsCode'] = $sSmsCode;
	}

	/**
	 * @return bool
	 */
	public
	function getFlagSmsSent()
	{
		return (!empty(Yii::app()->session['flagSmsSent']));
	}

	/**
	 * @param bool $bFlagSmsSent
	 */
	public
	function setFlagSmsSent($bFlagSmsSent)
	{
		Yii::app()->session['flagSmsSent'] = $bFlagSmsSent;
	}

	public
	function clearClientSession()
	{
		//сбрасываем шаги заполнения анкеты в 0
		Yii::app()->session['current_step'] = 0;
		Yii::app()->session['done_steps'] = 0;

		//удаляем флаги
		Yii::app()->session['flagSmsSent'] = null;
		Yii::app()->session['smsCountTries'] = null;
		Yii::app()->session['smsCode'] = null;

		//удаляем идентификаторы
		Yii::app()->session['client_id'] = null;
		Yii::app()->session['tmp_client_id'] = null;

		//чистим данные форм
		Yii::app()->session['ClientSelectProductForm'] = null;
		Yii::app()->session['ClientSelectGetWayForm'] = null;
		Yii::app()->session['ClientPersonalDataForm'] = null;
		Yii::app()->session['ClientAddressForm'] = null;
		Yii::app()->session['ClientJobInfoForm'] = null;
		Yii::app()->session['ClientSendForm'] = null;
	}


	/**
	 * @param $iLength
	 *
	 * @return string
	 */
	private
	function generateSMSCode($iLength = SiteParams::C_SMSCODE_LENGTH)
	{
		// генерация рандомного кода
		list($usec, $sec) = explode(' ', microtime());
		$fSeed = (float)$sec + ((float)$usec * 100000);

		mt_srand($fSeed);

		$sMin = "1";
		$sMax = "9";
		for ($i = 0; $i < $iLength; ++$i) {
			$sMin .= "0";
			$sMax .= "9";
		}

		$sGeneratedCode = mt_rand((int)$sMin, (int)$sMax);
		$sGeneratedCode = substr($sGeneratedCode, 1, $iLength);

		return $sGeneratedCode;
	}

	public
	function checkFiles($aFiles)
	{
		if (!isset($aFiles) || gettype($aFiles) != 'array') {
			return false;
		}

		foreach ($aFiles as $sFile) {
			if (!file_exists($sFile) || !getimagesize($sFile)) {
				return false;
			}
		}

		return true;
	}

	public
	function checkIdentificationFiles()
	{
		$iClientId = $this->getClientId();

		$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $iClientId . '/';

		$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
		$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
		$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
		$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_LAST . '.png';
		$aFiles[] = $sFilesPath . ImageController::C_TYPE_DOCUMENT . '.png';
		$aFiles[] = $sFilesPath . ImageController::C_TYPE_PHOTO . '.png';

		if ($this->checkFiles($aFiles)) {
			return true;
		}

		return false;
	}

	public
	function checkTmpIdentificationFiles($sIdentType = null)
	{
		$sTmpClientId = $this->getTmpClientId();

		$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $sTmpClientId . '/';

		if ($sIdentType === "documents") {
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_LAST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_DOCUMENT . '.png';
		} elseif ($sIdentType === "photo") {
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PHOTO . '.png';
		} else {
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PASSPORT_LAST . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_DOCUMENT . '.png';
			$aFiles[] = $sFilesPath . ImageController::C_TYPE_PHOTO . '.png';
		}

		if ($this->checkFiles($aFiles)) {
			return true;
		}

		return false;
	}


	public
	function moveIdentificationFiles()
	{
		$sTmpClientId = $this->getTmpClientId();
		$iClientId = $this->getClientId();

		$sFilesPath = Yii::app()->basePath . ImageController::C_IMAGES_DIR . $sTmpClientId . '/';

		$aFiles[] = ImageController::C_TYPE_PHOTO . '.png';
		$aFiles[] = ImageController::C_TYPE_PASSPORT_FRONT_FIRST . '.png';
		$aFiles[] = ImageController::C_TYPE_PASSPORT_FRONT_SECOND . '.png';
		$aFiles[] = ImageController::C_TYPE_PASSPORT_NOTIFICATION . '.png';
		$aFiles[] = ImageController::C_TYPE_PASSPORT_LAST . '.png';
		$aFiles[] = ImageController::C_TYPE_DOCUMENT . '.png';

		return $this->moveFiles($aFiles, $sFilesPath, Yii::app()->basePath . ImageController::C_IMAGES_DIR . $iClientId . '/');
	}

	public
	function moveFiles($aFiles, $sOldPath, $sNewPath)
	{
		if (!file_exists($sNewPath)) {
			@mkdir($sNewPath);
		} else {
			self::deleteDir($sNewPath);
			@mkdir($sNewPath);
		}
		foreach ($aFiles as $sFile) {
			if (!@rename($sOldPath . $sFile, $sNewPath . $sFile)) {
				return false;
			}
		}
		@rmdir($sOldPath);

		return true;
	}

	public
	static function deleteDir($dirPath)
	{
		if (!is_dir($dirPath)) {
			return;
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = @glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				@unlink($file);
			}
		}
		@rmdir($dirPath);
	}
}
