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
	const SITE1 = 'main';
	const SITE2 = 'ivanovo';

	//имя модели, в которой сохраняется телефон клиента
	const C_PHONE_MODEL_NAME = 'ClientPersonalDataForm';

	private $iClientId;
	private $iCurrentStep;
	private $iDoneSteps;

	/**
	 * Массив свойств для виджета сквозной выбор продукта
	 *
	 * @var array
	 */
	public static $aSelectProductSettings = array(
		self::SITE1 => array(
			'view'       => 'main',
			'model_name' => 'ClientSelectProductForm',
		),
		self::SITE2 => array(
			'view'       => 'flexible',
			'model_name' => 'ClientFlexibleProductForm',
		),
	);


	/**
	 * Конфигурация шагов заполнения анкеты:
	 * максимальный шаг, минимальный шаг, шаг по-умолчанию (на него переводит в случае, если текущий шаг вне пределов
	 * минимума-максимума)
	 *
	 * @var array
	 */

	public static $aSteps = array(
		self::SITE1 => array(
			'max' => 7,
			'min'     => 0,
			'default' => 0,
		),
		self::SITE2 => array(
			'max' => 6,
			'min'     => 0,
			'default' => 0,
		),

	);

	/**
	 * Конфигурация шагов заполнения анкеты
	 * массив должен содержать минимум 1 подмассив сайта (либо несколько массивов для разных сайтов)
	 *
	 * обязательные параметры: view, model
	 *
	 * view - может быть строкой либо array('condition'=>'methodName,true=>'view1',false=>'view2')
	 * condition - именя функции проверки
	 * true & false - какие view выбрать по результату проверки
	 *
	 * sub_view - аналогично view, этот параметр не обязателен, может содержать имя view для включения в основной view,
	 * поддерживает в т.ч. condition
	 *
	 * modelDbRelations - массив вида array('id','phone','name'), содержит список полей для запроса в БД
	 * запрос делается по client_id, т.е. клиент должен уже существовать в БД
	 * при вызове getFormModel() данные параметры будут запрошены и их значения помещены в модель,
	 * это требуется для валидации некоторых значений, которые связаны с данными в БД, сохраненными туда другими формами
	 * (на предыдущих шагах)
	 *
	 * @var array
	 */

	private static $aStepsInfo = array(
		self::SITE1 => array(
			0 => array(
				'view'             => 'client_select_product',
				'model'            => 'ClientSelectProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
			),
			1 => array(
				'view'             => 'infographic',
				'model'            => 'ClientSelectProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'infographic',
			),
			2 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/personal_data',
				'model'            => 'ClientPersonalDataForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'personal_data',
			),
			3 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/passport_data',
				'model'            => 'ClientPassportDataForm',
				'modelDbRelations' => array(
					'birthday'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'passport_data',
			),
			4 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/address_data',
				'model'            => 'ClientAddressDataForm',
				'modelDbRelations' => array(
					'phone'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'address_data',
			),
			5 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/job_data',
				'model'            => 'ClientJobDataForm',
				'modelDbRelations' => array(
					'friends_phone',
					'relatives_one_phone',
					'phone'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'job_data',
			),
			6 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/secret_data',
				'model'            => 'ClientSecretDataForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'secret_data',
			),
			7 => array(
				'view'             => 'client_form',
				'sub_view'         => array(
					'condition' => 'getFlagSmsSent',
					true        => 'confirm_phone/check_sms_code',
					false       => 'confirm_phone/send_sms_code'
				),
				'model'            => 'ClientConfirmPhoneViaSMSForm',
				'breadcrumbs_step' => 3,
				'metrika_goal'     => 'sms_code',
			),
		),
		self::SITE2 => array(
			0 => array(
				'view'             => 'client_flexible_product',
				'model'            => 'ClientFlexibleProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
			),
			1 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/personal_data',
				'model'            => 'ClientPersonalDataForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'personal_data',
			),
			2 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/passport_data',
				'model'            => 'ClientPassportDataForm',
				'modelDbRelations' => array(
					'birthday'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'passport_data',
			),
			3 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/address_data',
				'model'            => 'ClientAddressDataForm',
				'modelDbRelations' => array(
					'phone'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'address_data',
			),
			4 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/job_data',
				'model'            => 'ClientJobDataForm',
				'modelDbRelations' => array(
					'friends_phone',
					'relatives_one_phone',
					'phone'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'job_data',
			),
			5 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/secret_data',
				'model'            => 'ClientSecretDataForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'secret_data',
			),
			6 => array(
				'view'             => 'client_form',
				'sub_view'         => array(
					'condition' => 'getFlagSmsSent',
					true        => 'confirm_phone/check_sms_code',
					false       => 'confirm_phone/send_sms_code'
				),
				'model'            => 'ClientConfirmPhoneViaSMSForm',
				'breadcrumbs_step' => 3
			),
		),
	);

	/**
	 *
	 */
	public function init()
	{
		$this->iClientId = $this->getClientId();
		if (!$this->iClientId) {
			$this->iClientId = false;
		}

		$iDefaultStep = $this->getDefaultStep();

		$this->iCurrentStep = $this->getCurrentStep();
		if (!$this->iCurrentStep) {
			$this->setCurrentStep($iDefaultStep);
		}
		$this->iDoneSteps = $this->getDoneSteps();
		if (!$this->iDoneSteps) {
			$this->setDoneSteps($iDefaultStep);
		}
	}

	/**
	 * Проверяет, отправлены ли данные с помощью ajax.
	 * НЕ выполняет валидацию модели.
	 *
	 * @return bool
	 */
	public function isNeedAjaxValidation()
	{
		$bIsAjax = Yii::app()->request->getIsAjaxRequest();
		$sAjaxClass = Yii::app()->request->getParam('ajax');

		return ($bIsAjax && $sAjaxClass);
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

		if (get_class($oClientForm) === self::C_PHONE_MODEL_NAME) {
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
					$aValidFormData['channel_id'] = $this->getSessionChannel();
				}

				if (empty($aValidFormData['flex_amount'])) {
					$aValidFormData['flex_amount'] = $this->getSessionFlexibleProductAmount();
				}
				if (empty($aValidFormData['flex_time'])) {
					$aValidFormData['flex_time'] = $this->getSessionFlexibleProductTime();
				}
				if (SiteParams::getIsIvanovoSite()) {
					$aValidFormData['entry_point'] = 8;
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
	 * Сохраняет выбранные продукт/канал в сессию и/или в базу
	 *
	 * @param ClientCreateFormAbstract $oClientForm
	 */
	public function saveSelectedProduct(ClientCreateFormAbstract $oClientForm)
	{
		$aValidFormData = $oClientForm->getValidAttributes();

		if ($this->iClientId) {
			ClientData::saveClientDataById($aValidFormData, $this->iClientId);
			$aValidFormData['client_id'] = $this->iClientId;
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
		if (get_class($oClientForm) === self::C_PHONE_MODEL_NAME) {

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
					$aClientFormData['channel_id'] = $this->getSessionChannel();
				}
				if (empty($aClientFormData['flex_amount'])) {
					$aClientFormData['flex_amount'] = $this->getSessionFlexibleProductAmount();
				}
				if (empty($aClientFormData['flex_time'])) {
					$aClientFormData['flex_time'] = $this->getSessionFlexibleProductTime();
				}
				if (SiteParams::getIsIvanovoSite()) {
					$aClientFormData['entry_point'] = 8;
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

		//$bSmsSentOk = SmsGateSender::getInstance()->send('7' . $sPhone, $sMessage);

		//отправляем СМС через API
		$bSmsSentOk = Yii::app()->adminKreddyApi->sendSms($sPhone, $sMessage);

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


	public function resetSteps()
	{
		$iDefaultStep = $this->getDefaultStep();
		$this->setCurrentStep($iDefaultStep);
		$this->setDoneSteps($iDefaultStep);
	}

	public function getMetrikaGoalByStep()
	{
		$sSite = self::getSite();

		$sGoal = self::$aStepsInfo[$sSite][$this->iCurrentStep]['metrika_goal'];

		return $sGoal;
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
	 * @return int
	 */
	public function getDefaultStep()
	{
		$sSite = $this->getSite();

		return isset(self::$aSteps[$sSite]['default']) ? self::$aSteps[$sSite]['default'] : 0;

	}

	/**
	 * @return int
	 */
	public function getMaxStep()
	{
		$sSite = $this->getSite();

		return isset(self::$aSteps[$sSite]['max']) ? self::$aSteps[$sSite]['max'] : 0;

	}

	/**
	 * @return int
	 */
	public function getMinStep()
	{
		$sSite = $this->getSite();

		return isset(self::$aSteps[$sSite]['min']) ? self::$aSteps[$sSite]['min'] : 0;

	}

	/**
	 * Возвращает модель текущей формы.
	 *
	 * @return ClientCreateFormAbstract
	 */

	public function getFormModel()
	{
		/**
		 * * @var ClientCreateFormAbstract $oModel
		 */

		//todo: учесть, что могут быть статические страницы (инфографика)

		$sSite = self::getSite();

		$sModel = self::$aStepsInfo[$sSite][$this->iCurrentStep]['model'];

		//создаем модель
		$oModel = new $sModel();

		//если есть связи с полями в БД, то нужно сделать запрос в БД с указанием этих полей и получить их данные
		//это требуется для валидации данных в формах, в которых правила валидации связаны с данными из БД
		//соответствующее поле добавляется в модель формы, и тут заполняется данными
		if (isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['modelDbRelations'])) {
			$aRelations = self::$aStepsInfo[$sSite][$this->iCurrentStep]['modelDbRelations'];
			$iClientId = $this->getClientId();
			$oClientData = ClientData::model()->findByPk($iClientId);
			//если данные клиента найдены
			if ($oClientData) {
				//получаем требуемые атрибуты
				$aClientData = $oClientData->getAttributes($aRelations);
				//записываем в модель
				$oModel->setAttributes($aClientData);
			}
		}

		return $oModel;
	}

	/**
	 * Возвращает название необходимого для генерации представления.
	 *
	 * @return array
	 */
	public function getView()
	{
		$sSite = self::getSite();

		$mView = self::$aStepsInfo[$sSite][$this->iCurrentStep]['view'];
		$mSubView = (isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['sub_view']))
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['sub_view']
			: null;

		//если массив вида array('condition'=>'someCondition,...);
		if (is_array($mView) && isset($mView['condition'])) {
			$bCondition = $this->$mView['condition']();
			$sView = $mView[$bCondition];
		} else {
			$sView = $mView;
		}

		//если массив вида array('condition'=>'someCondition,...);
		if (is_array($mSubView) && isset($mSubView['condition'])) {
			$bCondition = $this->$mSubView['condition']();
			$sSubView = $mSubView[$bCondition];
		} else {
			$sSubView = $mSubView;
		}

		$aView = array(
			'view'     => $sView,
			'sub_view' => $sSubView,
		);

		return $aView;
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|null
	 */
	public function getPostData()
	{
		$sSite = self::getSite();

		$sModel = self::$aStepsInfo[$sSite][$this->iCurrentStep]['model'];

		return Yii::app()->request->getParam($sModel);
	}

	/**
	 * @return string
	 */
	public static function getSelectProductView()
	{
		$sSite = self::getSite();
		$sView = isset(self::$aSelectProductSettings[$sSite]['view']) ? self::$aSelectProductSettings[$sSite]['view'] : '';

		return $sView;
	}

	/**
	 * @return string
	 */
	public static function getSelectProductModelName()
	{
		$sSite = self::getSite();
		$sModelName = isset(self::$aSelectProductSettings[$sSite]['model_name']) ? self::$aSelectProductSettings[$sSite]['model_name'] : '';

		return $sModelName;
	}

	/**
	 * @return string
	 */
	public static function getSite()
	{
		return (SiteParams::getIsIvanovoSite())
			? self::SITE2
			: self::SITE1;
	}

	/**
	 * Переводит обработку форм на следующий шаг
	 * @param int $iSteps
	 */
	public function nextStep($iSteps = 1)
	{
		//TODO делать проверку max step
		$this->iCurrentStep += $iSteps;

		if ($this->iDoneSteps < $this->iCurrentStep) {
			$this->iDoneSteps = $this->iCurrentStep;
			Yii::app()->session['done_steps'] = $this->iDoneSteps;
		}
		Yii::app()->session['current_step'] = $this->iCurrentStep;
	}

	/**
	 * @return bool|string
	 */
	public function getSessionPhone()
	{
		if (isset(Yii::app()->session[self::C_PHONE_MODEL_NAME]['phone'])) {
			$sPhone = Yii::app()->session[self::C_PHONE_MODEL_NAME]['phone'];
		} else {
			$sPhone = false;
		}

		return $sPhone;
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
	 * Получение из сессии суммы выбранного "гибкого" займа
	 *
	 * @return bool
	 */
	public function getSessionFlexibleProductAmount()
	{

		return isset(Yii::app()->session['ClientFlexibleProductForm']['amount'])
			? Yii::app()->session['ClientFlexibleProductForm']['amount']
			: false;
	}

	/**
	 * Получение из сессии времени выбранного "гибкого" займа
	 * @return bool
	 */
	public function getSessionFlexibleProductTime()
	{

		return isset(Yii::app()->session['ClientFlexibleProductForm']['time'])
			? Yii::app()->session['ClientFlexibleProductForm']['time']
			: false;
	}

	/**
	 * @return string|bool
	 */
	public function getSessionChannel()
	{
		if (SiteParams::getIsIvanovoSite()) {
			return isset(Yii::app()->session['ClientFlexibleProductForm']['channel_id'])
				? Yii::app()->session['ClientFlexibleProductForm']['channel_id']
				: false;
		}

		return isset(Yii::app()->session['ClientSelectProductForm']['channel_id'])
			? Yii::app()->session['ClientSelectProductForm']['channel_id']
			: false;
	}

	/**
	 * @param string $sProduct
	 */
	public function goSelectProduct($sProduct = 'Покупки')
	{
		$aProducts = Yii::app()->adminKreddyApi->getProducts();
		foreach ($aProducts as $i => $aProduct) {
			if (array_search($sProduct, $aProduct)) {
				$aShoppingProduct = $i;
			}
		}
		if (isset($aShoppingProduct)) {
			Yii::app()->session['ClientSelectProductForm'] = array('product' => $aShoppingProduct);

			Yii::app()->session['goShopping'] = true;
			$this->nextStep();
		}
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
		//сбрасываем шаги заполнения анкеты
		$this->resetSteps();

		//удаляем идентификаторы
		Yii::app()->session['client_id'] = null;
		Yii::app()->session['tmp_client_id'] = null;

		//TODO для чистки брать все имена форм из массива конфигурации
		//чистим данные форм
		Yii::app()->session['ClientSelectProductForm'] = null;
		Yii::app()->session['ClientSelectChannelForm'] = null;
		Yii::app()->session['ClientPersonalDataForm'] = null;
		Yii::app()->session['ClientAddressForm'] = null;
		Yii::app()->session['ClientJobInfoForm'] = null;
		Yii::app()->session['ClientSendForm'] = null;
		Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = null;

		Yii::app()->session['ClientSelectProductForm'] = null;
		Yii::app()->session['ClientFlexibleProductForm'] = null;
		Yii::app()->session['ClientFullForm'] = null;
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

	/**
	 * Метод для получения breadcrumbs для страниц формы
	 *
	 * @return array
	 */
	public function getBreadCrumbs()
	{
		return (SiteParams::getIsIvanovoSite()) ? SiteParams::$aIvanovoBreadCrumbs : SiteParams::$aMainBreadCrumbs;
	}

	/**
	 * @return int|null
	 */
	public function getBreadCrumbsStep()
	{
		$sSite = self::getSite();

		$iBreadCrumbsStep = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['breadcrumbs_step'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['breadcrumbs_step']
			: null;

		return $iBreadCrumbsStep;

	}

}
