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

		if (get_class($oClientForm) === 'ClientPersonalDataForm' || get_class($oClientForm) === 'ClientFullForm2') {
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
				if (empty($aValidFormData['product'])) {
					$aValidFormData['product'] = $this->getSessionProduct();
				}
				if (empty($aValidFormData['get_way'])) {
					$aValidFormData['get_way'] = $this->getSessionGetWay();
				}
				$aValidFormData['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aValidFormData['ip'] = Yii::app()->request->getUserHostAddress();
				$aValidFormData['identification_type'] = $this->getIdentType();
				ClientData::saveClientDataById($aValidFormData, $this->client_id);

			}
		} else {
			if ($this->client_id) {
				ClientData::saveClientDataById($aValidFormData, $this->client_id);
				$aValidFormData['client_id'] = $this->client_id;
			}
		}

		$aSessionFormData = $this->getSessionFormData($oClientForm);

		//проверяем, есть ли в сессии уже какие-то данные, и проверяем что они лежат в массиве
		if (!empty($aSessionFormData) && gettype($aSessionFormData) == "array") {
			//объединяем данные из сессии с новыми валидными данными
			$aValidFormData = array_merge($aSessionFormData, $aValidFormData);
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
		if (get_class($oClientForm) === 'ClientPersonalDataForm' || get_class($oClientForm) === 'ClientFullForm2') {

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

				if (empty($aClientFormData['product'])) {
					$aClientFormData['product'] = $this->getSessionProduct();
				}
				if (empty($aClientFormData['get_way'])) {
					$aClientFormData['get_way'] = $this->getSessionGetWay();
				}

				$aClientFormData['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aClientFormData['ip'] = Yii::app()->request->getUserHostAddress();
				$aClientFormData['identification_type'] = $this->getIdentType();
				ClientData::saveClientDataById($aClientFormData, $this->client_id);

				//TODO выпилить флаг идентификации отсюда и из БД
				$aClientData['flag_identified'] = 1;
				ClientData::saveClientDataById($aClientData, $this->client_id);
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
	 * Делает запрос на отправку SMS и возвращает true либо текст ошибки
	 *
	 * @return bool|string
	 */
	public function sendSmsCode()
	{
		// если с данного ip нельзя запросить SMS, выдаём ошибку
		if (!Yii::app()->antiBot->checkSmsRequest()) {
			return Dictionaries::C_ERR_GENERAL;
		}

		$iClientId = $this->getClientId();
		$aClientForm = ClientData::getClientDataById($iClientId);

		// если код уже есть в базе, выдаём ошибку - SMS уже отправлено
		if (!empty($aClientForm['sms_code'])) {
			return Dictionaries::C_ERR_SMS_SENT;
		}

		$sPhone = $this->getSessionPhone();
		$sSmsCode = $this->getSmsCode();

		// проверяем, есть ли код в сессии. если нет - генерируем и записываем в сессию
		if ($sSmsCode === false) {
			$sSmsCode = $this->generateSMSCode(SiteParams::C_SMSCODE_LENGTH);
			$this->setSmsCode($sSmsCode);
		}

		// если в сессии есть телефон и код
		if ($sPhone !== false && $sSmsCode !== false) {
			//отправляем СМС
			$sMessage = "Ваш код подтверждения: " . $sSmsCode;
			SmsGateSender::getInstance()->send('7' . $sPhone, $sMessage);

			//добавляем в лог запрос sms с этого ip
			Yii::app()->antiBot->addSmsRequest();

			// и записываем в сессию, а также в БД
			$this->setFlagSmsSent(true);
			$aClientForm['sms_code'] = $sSmsCode;
			ClientData::saveClientDataById($aClientForm, $iClientId);

			// возвращаем true
			return true;
		} else {
			// Ошибка при отправке SMS - некуда или нечего отправлять
			return Dictionaries::C_ERR_SMS_CANT_SEND;
		}

	}

	/**
	 * Сверяет код с кодом из базы
	 *
	 * @param array $aPostData
	 *
	 * @return mixed
	 */
	public function checkSmsCode($aPostData)
	{
		$client_id = $this->getClientId();
		$iSmsCountTries = $this->getSmsCountTries();

		$oClientSmsForm = new ClientConfirmPhoneViaSMSForm();
		$oClientSmsForm->setAttributes($aPostData);

		// если число попыток ввода кода меньше максимально допустимых
		if ($iSmsCountTries < SiteParams::MAX_SMSCODE_TRIES) {
			// если введённые данные валидны и совпадают с кодом из базы
			if ($oClientSmsForm->validate()
				&& ClientData::compareSMSCodeByClientId($oClientSmsForm->sms_code, $client_id)
			) {
				// подтверждение по SMS выполнено успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
				$aData['flag_sms_confirmed'] = 1;
				ClientData::saveClientDataById($aData, $client_id);

				//очищаем сессию (данные формы и прочее)
				$this->clearClientSession();
				//ставим флаг "форма отправлена" для отображения представления с сообщением "Форма отправлена"
				//TODO выпилить
				$this->setFormSent(true);

				// успешная проверка
				return true;
			} else {
				$iSmsCountTries += 1;
				$this->setSmsCountTries($iSmsCountTries);

				// если это была последняя попытка
				if ($iSmsCountTries == SiteParams::MAX_SMSCODE_TRIES) {
					// возвращаем сообщение о превышении числа попыток
					$this->clearClientSession();

					return Dictionaries::C_ERR_SMS_TRIES;
				} else {
					// выводим сообщение - код неверен + сколько осталось попыток
					$iTriesLeft = SiteParams::MAX_SMSCODE_TRIES - $iSmsCountTries;

					return (Dictionaries::C_ERR_SMS_WRONG . ' ' . Dictionaries::C_ERR_TRIES_LEFT . $iTriesLeft);
				}
			}
		} else {
			// возвращаем сообщение о превышении числа попыток
			$this->clearClientSession();

			return Dictionaries::C_ERR_SMS_TRIES;
		}
	}

	/**
	 * @param $iClientId
	 */

	public function sendClientToApi($iClientId)
	{
		$aClientData = ClientData::getClientDataById($iClientId);

		//создаем клиента в admin.kreddy.ru через API
		//получаем от API авторизацию (сообщение что токен получен)
		//и логиним юзера
		return (Yii::app()->adminKreddyApi->createClientAndLogIn($aClientData));
	}

	/**
	 * Возвращает номер текущего шага (нумерация с нуля)
	 *
	 * @return int
	 */
	public function getCurrentStep()
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
		$bFullForm = SiteParams::B_FULL_FORM;

		if ($bFullForm) {
			switch ($this->current_step) {
				case 0:
					return new ClientSelectProductForm2();
					break;
				case 1:
					return new ClientFullForm2();
					break;
				case 2:
				case 3:
					return new ClientConfirmPhoneViaSMSForm();
					break;
				default:
					return new ClientSelectProductForm2();
					break;
			}
		} else {

			switch ($this->current_step) {
				case 0:
					return new ClientSelectProductForm();
					break;
				case 1:
					return new ClientSelectGetWayForm();
					break;
				case 2:
					return new ClientPersonalDataForm();
					break;
				case 3:
					return new ClientAddressForm();
					break;
				case 4:
					return new ClientJobInfoForm();
					break;
				case 5:
					return new ClientSendForm();
					break;
				case 6:
				case 7:
					return new ClientConfirmPhoneViaSMSForm();
					break;
				default:
					return new ClientSelectProductForm();
					break;
			}

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
		//если в сессии стоит флаг "отобразить form_sent" то передаем это представление
		if ($this->isFormSent()) {
			return 'form_sent';
		}

		$bFullForm = SiteParams::B_FULL_FORM;

		if ($bFullForm) {

			switch ($this->current_step) {
				case 0:
					return 'client_select_product2';
					break;
				case 1:
					return 'client_full_form2';
					break;
				case 2:
					return 'confirm_phone_full_form2/send_sms_code';
					break;
				case 3:
					return 'confirm_phone_full_form2/check_sms_code';
					break;
				default:
					return 'client_select_product2';
					break;
			}
		} else {


			switch ($this->current_step) {
				case 0:
					return 'client_select_product';
					break;
				case 1:
					return 'client_select_get_way';
					break;
				case 2:
					return 'client_personal_data';
					break;
				case 3:
					return 'client_address';
					break;
				case 4:
					return 'client_job_info';
					break;
				case 5:
					return 'client_send';
					break;
				case 6:
					return 'confirm_phone/send_sms_code';
					break;
				case 7:
					return 'confirm_phone/check_sms_code';
					break;
				default:
					return 'client_select_product';
					break;
			}
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

		$bFullForm = SiteParams::B_FULL_FORM;

		if ($bFullForm) {
			switch ($this->current_step) {
				case 0:
				{
					//TODO: заменить на Yii::app()->request->getParam('')
					if (isset($_POST['ClientSelectProductForm2'])) {
						return $_POST['ClientSelectProductForm2'];
					}

					return null;
				}
					break;
				case 1:
				{
					if (isset($_POST['ClientFullForm2'])) {
						return $_POST['ClientFullForm2'];
					}

					return null;
				}
					break;
				case 2:
				case 3:
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
		} else {
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
					if (isset($_POST['ClientPersonalDataForm'])) {
						return $_POST['ClientPersonalDataForm'];
					}

					return null;
				}
					break;
				case 3:
				{
					if (isset($_POST['ClientAddressForm'])) {
						return $_POST['ClientAddressForm'];
					}

					return null;
				}
					break;
				case 4:
				{
					if (isset($_POST['ClientJobInfoForm'])) {
						return $_POST['ClientJobInfoForm'];
					}

					return null;
				}
					break;
				case 5:
				{
					if (isset($_POST['ClientSendForm'])) {
						return $_POST['ClientSendForm'];
					}

					return null;
				}
					break;
				case 6:
				case 7:
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
	}

	/**
	 * @param $iIdentCode
	 */
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
				Yii::app()->session['InviteToIdentificationForm'] = array('go_identification' => $iIdentCode);
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
	 * @return bool|string
	 */
	public
	function getSessionPhone()
	{
		if (!SiteParams::B_FULL_FORM && isset(Yii::app()->session['ClientPersonalDataForm']['phone'])) {
			$sPhone = Yii::app()->session['ClientPersonalDataForm']['phone'];
		} elseif (isset(Yii::app()->session['ClientFullForm2']['phone'])) {
			$sPhone = Yii::app()->session['ClientFullForm2']['phone'];
		} else {
			$sPhone = false;
		}

		return $sPhone;
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
	 * @return null
	 */
	public function getIdentType()
	{
		return (isset(Yii::app()->session['InviteToIdentificationForm']['go_identification'])) ? Yii::app()->session['InviteToIdentificationForm']['go_identification'] : null;
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

		$aSessionFormData = Yii::app()->session[get_class($oClientForm)];
		if (isset($aSessionFormData['password'])) {
			$aSessionFormData['password'] = '';
		}
		if (isset($aSessionFormData['password_repeat'])) {
			$aSessionFormData['password_repeat'] = '';
		}

		return $aSessionFormData;
	}

	/**
	 * @return int номер выбранного продукта
	 */
	public
	function getSessionProduct()
	{
		if (!SiteParams::B_FULL_FORM) {
			return Yii::app()->session['ClientSelectProductForm']['product'];
		} else {
			return Yii::app()->session['ClientSelectProductForm2']['product'];
		}
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
		return (isset(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCountTries'])) ? Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCountTries'] : 0;
	}

	/**
	 * @param int $iSmsCountTries
	 */
	public
	function setSmsCountTries($iSmsCountTries)
	{
		$array = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
		$array['smsCountTries'] = $iSmsCountTries;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $array;
	}

	/**
	 * @return string|bool
	 */
	public function getSmsCode()
	{
		return (isset(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCode'])) ? Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCode'] : false;
	}

	/**
	 * @param $sSmsCode
	 */
	public
	function setSmsCode($sSmsCode)
	{
		$array = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
		$array['smsCode'] = $sSmsCode;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $array;
	}

	/**
	 * @return bool
	 */
	public
	function getFlagSmsSent()
	{
		return (!empty(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['flagSmsSent']));
	}

	/**
	 * @param bool $bFlagSmsSent
	 */
	public
	function setFlagSmsSent($bFlagSmsSent)
	{
		$array = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
		$array['flagSmsSent'] = $bFlagSmsSent;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $array;
	}

	public
	function clearClientSession()
	{
		//сбрасываем шаги заполнения анкеты в 0
		Yii::app()->session['current_step'] = 0;
		Yii::app()->session['done_steps'] = 0;

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
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = null;

		Yii::app()->session['ClientSelectProductForm2'] = null;
		Yii::app()->session['ClientFullForm2'] = null;
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

	/**
	 * @param $aFiles
	 *
	 * @return bool
	 */
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

	/**
	 * @return bool
	 */
	public
	function isFormSent()
	{
		return (!empty(Yii::app()->session['isFormSent'])) ? Yii::app()->session['isFormSent'] : false;
	}

	/**
	 * @param $bFormSent
	 *
	 */
	public
	function setFormSent($bFormSent)
	{
		Yii::app()->session['isFormSent'] = $bFormSent;
	}
}
