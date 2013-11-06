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
	private $iClientId;
	private $iCurrentStep;
	private $iDoneSteps;

	public function init()
	{
		if (!$this->iClientId = $this->getClientId()) {
			$this->iClientId = false;
		}

		if (!$this->iCurrentStep = $this->getCurrentStep()) {
			$this->setCurrentStep(0);
		}

		if (!$this->iDoneSteps = $this->getDoneSteps()) {
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

		if (get_class($oClientForm) === 'ClientFullForm') {
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
					$this->iClientId = $aCookieData['client_id'];
					$this->setClientId($this->iClientId);

				} else {
					/**
					 * функция addClient()ищет клиента в базе по телефону,
					 * и если находит - возвращает запись с указанным телефоном как результат,
					 * либо создает новую запись
					 */
					$oClientData = ClientData::addClient($aValidFormData['phone']);
					Yii::app()->antiBot->addFormRequest();
					$this->setClientId($oClientData->client_id);
					$this->iClientId = $oClientData->client_id;

					$aCookieData = array('client_id' => $oClientData->client_id, 'phone' => $aValidFormData['phone']);
					Cookie::saveDataToCookie('client', $aCookieData);
				}
			}

			if ($this->iClientId) {
				if (empty($aValidFormData['product'])) {
					$aValidFormData['product'] = $this->getSessionProduct();
				}
				if (empty($aValidFormData['channel_id'])) {
					$aValidFormData['channel_id'] = $this->getSessionChannelId();
				}
				$aClientDataForSave = $aValidFormData;

				$aClientDataForSave['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aClientDataForSave['ip'] = Yii::app()->request->getUserHostAddress();
				ClientData::saveClientDataById($aClientDataForSave, $this->iClientId);

			}
		} else {
			if ($this->iClientId) {
				ClientData::saveClientDataById($aValidFormData, $this->iClientId);
				$aValidFormData['client_id'] = $this->iClientId;
			}
		}

		$aSessionFormData = $this->getSessionFormData($oClientForm);

		//проверяем, есть ли в сессии уже какие-то данные, и проверяем что они лежат в массиве
		if (!empty($aSessionFormData) && is_array($aSessionFormData) && is_array($aValidFormData)) {
			//объединяем данные из сессии с новыми валидными данными
			$aValidFormData = array_merge($aSessionFormData, $aValidFormData);
		} elseif (!empty($aSessionFormData) && is_array($aSessionFormData)) {
			$aValidFormData = $aSessionFormData;
		}

		Yii::app()->session[get_class($oClientForm)] = $aValidFormData;
		Yii::app()->session[get_class($oClientForm) . '_client_id'] = $this->iClientId;

		return;
	}

	/**
	 * Выполняет обработку данных формы после проверки.
	 *
	 * @param ClientCreateFormAbstract|ClientSelectChannelForm $oClientForm
	 *
	 */
	public function formDataProcess(ClientCreateFormAbstract $oClientForm)
	{
		if (get_class($oClientForm) === 'ClientFullForm') {

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
				!empty($aCookieData['client_id']) &&
				!is_null(ClientData::getClientDataById($aCookieData['client_id']))
			) {
				$this->iClientId = $aCookieData['client_id'];
				$this->setClientId($this->iClientId);
			} else {
				/**
				 * функция addClient()ищет клиента в базе по телефону,
				 * и если находит - возвращает запись с указанным телефоном как результат
				 * либо создает новую запись
				 */

				$oClientData = ClientData::addClient($oClientForm['phone']);
				Yii::app()->antiBot->addFormRequest();
				$this->setClientId($oClientData->client_id);

				$this->iClientId = $oClientData->client_id;

				$aCookieData = array('client_id' => $oClientData->client_id, 'phone' => $oClientData->phone);
				Cookie::saveDataToCookie('client', $aCookieData);
			}
			if ($this->iClientId) {
				$aClientFormData = $oClientForm->getAttributes();

				if (empty($aClientFormData['product'])) {
					$aClientFormData['product'] = $this->getSessionProduct();
				}
				if (empty($aClientFormData['channel_id'])) {
					$aClientFormData['channel_id'] = $this->getSessionChannelId();
				}

				$aClientDataForSave = $aClientFormData;

				$aClientDataForSave['tracking_id'] = Yii::app()->request->cookies['TrackingID'];
				$aClientDataForSave['ip'] = Yii::app()->request->getUserHostAddress();
				ClientData::saveClientDataById($aClientDataForSave, $this->iClientId);
			}

			/**
			 * если номер, на который отправлялось СМС, не совпадает с введенным,
			 * т.е. клиент вернулся на анкету и ввел другой номер,
			 * то позволяем снова отправить СМС с кодом водтверждения
			 */
			if (!$this->compareSessionAndSentPhones()) {
				$this->setFlagSmsSent(false);
			}

			// ставим флаг, что полная форма заполнена - чтобы при возврате к ней была активна кнопка "Отправить"
			$this->setFlagFullFormFilled(true);
		} else {
			if ($this->iClientId) {
				$aClientFormData = $oClientForm->getAttributes();
				ClientData::saveClientDataById($aClientFormData, $this->iClientId);
			}
		}
		/**
		 * Сохраняем данные формы в сессию
		 */
		$aClientFormData = $oClientForm->getAttributes();

		Yii::app()->session[get_class($oClientForm)] = $aClientFormData;
		Yii::app()->session[get_class($oClientForm) . '_client_id'] = $this->iClientId;

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

		// если код уже есть в базе, ставим флаг и вовзращаем true
		if (!empty($aClientForm['sms_code']) && $this->compareSessionAndSentPhones()) {
			$this->setFlagSmsSent(true);

			return true;
		}

		$sPhone = $this->getSessionPhone();

		// если в сессии нет телефона, ошибка - некуда отправлять
		if ($sPhone === false) {
			return Dictionaries::C_ERR_SMS_CANT_SEND;
		}

		// генерируем Code
		$sSmsCode = $this->generateSMSCode(SiteParams::C_SMSCODE_LENGTH);

		//отправляем СМС
		$sMessage = "Ваш код подтверждения: " . $sSmsCode;

		$bSmsSentOk = SmsGateSender::getInstance()->send('7' . $sPhone, $sMessage);

		if ($bSmsSentOk) {
			//добавляем в лог запрос sms с этого ip
			Yii::app()->antiBot->addSmsRequest();
			$this->setSmsSentPhone($sPhone); //записываем, на какой номер было отправлено СМС

			$aClientForm['sms_code'] = $sSmsCode;
			// если не удалось записать в БД - общая ошибка
			if (!ClientData::saveClientDataById($aClientForm, $iClientId)) {
				return Dictionaries::C_ERR_GENERAL;
			}

			// ставим флаг успешной отправки
			$this->setFlagSmsSent(true);

			// возвращаем true
			return true;
		}

		return Dictionaries::C_ERR_SMS_CANT_SEND;
	}


	/**
	 * Сверяет код с кодом из базы
	 *
	 * @param array $aPostData
	 *
	 * @return string|integer
	 */
	public function checkSmsCode($aPostData)
	{
		$iClientId = $this->getClientId();
		$iSmsCountTries = $this->getSmsCountTries();

		$oClientSmsForm = new ClientConfirmPhoneViaSMSForm();
		$oClientSmsForm->setAttributes($aPostData);

		// если число попыток ввода кода меньше максимально допустимых
		if ($iSmsCountTries < SiteParams::MAX_SMSCODE_TRIES) {
			// если введённые данные валидны и совпадают с кодом из базы
			if ($oClientSmsForm->validate()
				&& ClientData::compareSMSCodeByClientId($oClientSmsForm->sms_code, $iClientId)
			) {
				// подтверждение по SMS выполнено успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
				$aData['flag_sms_confirmed'] = 1;
				ClientData::saveClientDataById($aData, $iClientId);

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
	 * @param $aClientData
	 *
	 * @return bool
	 */

	public function sendClientToApi($aClientData)
	{
		//создаем клиента в admin.kreddy.ru через API
		//получаем от API авторизацию (сообщение что токен получен)
		//и логиним юзера
		return (Yii::app()->adminKreddyApi->createClient($aClientData));
	}

	/**
	 * Возвращает номер текущего шага (нумерация с нуля)
	 *
	 * @return int
	 */
	public function getCurrentStep()
	{
		$this->iCurrentStep = Yii::app()->session['current_step'];

		return $this->iCurrentStep;
	}

	/**
	 * @param $iStep
	 */
	public function setCurrentStep($iStep)
	{
		Yii::app()->session['current_step'] = $iStep;
		$this->iCurrentStep = $iStep;
	}

	/**
	 * Возвращает число пройденных шагов (нумерация с нуля)
	 *
	 * @return int
	 */
	public function getDoneSteps()
	{
		$this->iDoneSteps = Yii::app()->session['done_steps'];

		return $this->iDoneSteps;
	}

	/**
	 * @param int $iSteps
	 */
	public function setDoneSteps($iSteps)
	{
		Yii::app()->session['done_steps'] = $iSteps;
		$this->iDoneSteps = $iSteps;
	}

	/**
	 * Возвращает модель текущей формы.
	 *
	 * @return ClientCreateFormAbstract
	 */

	public function getFormModel() //возвращает модель, соответствующую текущему шагу заполнения формы
	{


		switch ($this->iCurrentStep) {
			case 0:
				return new ClientSelectProductForm();
				break;
			case 1:
				return new ClientFullForm();
				break;
			case 2:
			case 3:
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
	public function getView()
	{

		switch ($this->iCurrentStep) {
			case 0:
				return 'client_select_product';
				break;
			case 1:
				return 'client_full_form';
				break;
			case 2:
				// если SMS уже отправлялось, рендер формы проверки
				if ($this->getFlagSmsSent()) {
					return 'confirm_phone_full_form/check_sms_code';
				}

				return 'confirm_phone_full_form/send_sms_code';
				break;
			case 3:
				// если SMS ещё не отправлялось, рендер формы отправки
				if (!$this->getFlagSmsSent()) {
					return 'confirm_phone_full_form/send_sms_code';
				}

				return 'confirm_phone_full_form/check_sms_code';
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
	public function getPostData()
	{


		switch ($this->iCurrentStep) {
			case 0:
				return Yii::app()->request->getParam('ClientSelectProductForm');
				break;
			case 1:
				return Yii::app()->request->getParam('ClientFullForm');
				break;
			case 2:
			case 3:
				return Yii::app()->request->getParam('ClientConfirmPhoneViaSMSForm');
				break;
			default:
				return null;
				break;

		}
	}


	/**
	 * Переводит обработку форм на следующий шаг
	 *
	 * @param int $iSteps
	 */
	public function nextStep($iSteps = 1)
	{
		if (!($iSteps <= 3)) {
			$iSteps = 1;
		}
		$this->iCurrentStep += $iSteps;
		Yii::app()->session['current_step'] = $this->iCurrentStep;
		if ($this->iDoneSteps < Yii::app()->session['current_step']) {
			Yii::app()->session['done_steps'] = $this->iDoneSteps = Yii::app()->session['current_step'];
		}
	}

	/**
	 * @return bool|string
	 */
	public function getSessionPhone()
	{
		if (isset(Yii::app()->session['ClientFullForm']['phone'])) {
			$sPhone = Yii::app()->session['ClientFullForm']['phone'];
		} else {
			$sPhone = false;
		}

		return $sPhone;
	}

	/**
	 * @param $bFullFormFilled
	 */
	public function setFlagFullFormFilled($bFullFormFilled)
	{
		Yii::app()->session['flagClientFullFormFilled'] = $bFullFormFilled;
	}

	/**
	 * Была ли уже заполнена полная форма?
	 *
	 * @return bool
	 */
	public function getFlagFullFormFilled()
	{
		return !empty(Yii::app()->session['flagClientFullFormFilled']);
	}

	/**
	 * @return int
	 */
	public function getClientId()
	{
		return Yii::app()->session['client_id'];
	}

	/**
	 * @param $iClientId
	 */
	public function setClientId($iClientId)
	{
		Yii::app()->session['client_id'] = $iClientId;
	}

	/**
	 * @return int
	 */
	public function getTmpClientId()
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
	public function getSessionFormClientId(ClientCreateFormAbstract $oClientForm)
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
	public function getSessionFormData(ClientCreateFormAbstract $oClientForm)
	{
		if (!isset($oClientForm)) {
			return null;
		}

		$aSessionFormData = Yii::app()->session[get_class($oClientForm)];
		if (isset($aSessionFormData['password'])) {
			unset($aSessionFormData['password']);
		}
		if (isset($aSessionFormData['password_repeat'])) {
			unset($aSessionFormData['password_repeat']);
		}

		return $aSessionFormData;
	}

	/**
	 * @return int|bool номер выбранного продукта
	 */
	public function getSessionProduct()
	{

		return isset(Yii::app()->session['ClientSelectProductForm']['product'])
			? Yii::app()->session['ClientSelectProductForm']['product']
			: false;
	}

	/**
	 * @return string|bool
	 */
	public function getSessionChannel()
	{
		return isset(Yii::app()->session['ClientSelectProductForm']['channel_id'])
			? Yii::app()->session['ClientSelectProductForm']['channel_id']
			: false;
	}

	public function goShopping()
	{
		$aProducts = Yii::app()->adminKreddyApi->getProducts();
		foreach ($aProducts as $i => $aProduct) {
			if (array_search('Покупки', $aProduct)) {
				$aShoppingProduct = $i;
			}
		}
		if (isset($aShoppingProduct)) {
			Yii::app()->session['ClientSelectProductForm'] = array('product'=> $aShoppingProduct);

			Yii::app()->session['goShopping'] = true;
			$this->nextStep();
		}
	}

	/**
	 * @return int номер выбранного спссоба
	 */
	public function getSessionChannelId()
	{
		return Yii::app()->session['ClientSelectChannelForm']['channel_id'];
	}

	/**
	 * @return int
	 */
	public function getSmsCountTries()
	{
		return (isset(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCountTries'])) ? Yii::app()->session['ClientConfirmPhoneViaSMSForm']['smsCountTries'] : 0;
	}

	/**
	 * @param int $iSmsCountTries
	 */
	public function setSmsCountTries($iSmsCountTries)
	{
		$array = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
		$array['smsCountTries'] = $iSmsCountTries;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $array;
	}

	/**
	 * @return bool
	 */
	public function getFlagSmsSent()
	{
		return (!empty(Yii::app()->session['ClientConfirmPhoneViaSMSForm']['flagSmsSent']));
	}

	/**
	 * @param bool $bFlagSmsSent
	 */
	public function setFlagSmsSent($bFlagSmsSent)
	{
		$array = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];
		$array['flagSmsSent'] = $bFlagSmsSent;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $array;
	}

	/**
	 * @param $sPhone
	 */
	public function setSmsSentPhone($sPhone)
	{
		Yii::app()->session['sSmsSentPhone'] = $sPhone;
	}

	/**
	 * @return string
	 */
	public function getSmsSentPhone()
	{
		return Yii::app()->session['sSmsSentPhone'];
	}

	public function clearClientSession()
	{
		//сбрасываем шаги заполнения анкеты в 0
		Yii::app()->session['current_step'] = 0;
		Yii::app()->session['done_steps'] = 0;

		//удаляем идентификаторы
		Yii::app()->session['client_id'] = null;
		Yii::app()->session['tmp_client_id'] = null;

		//чистим данные форм
		Yii::app()->session['ClientSelectProductForm'] = null;
		Yii::app()->session['ClientSelectChannelForm'] = null;
		Yii::app()->session['ClientPersonalDataForm'] = null;
		Yii::app()->session['ClientAddressForm'] = null;
		Yii::app()->session['ClientJobInfoForm'] = null;
		Yii::app()->session['ClientSendForm'] = null;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = null;

		Yii::app()->session['ClientSelectProductForm'] = null;
		Yii::app()->session['ClientFullForm'] = null;

		$this->setFlagFullFormFilled(false);
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
	public function checkFiles($aFiles)
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
	public function compareSessionAndSentPhones()
	{
		return (Yii::app()->clientForm->getSmsSentPhone() === Yii::app()->clientForm->getSessionPhone());
	}
}
