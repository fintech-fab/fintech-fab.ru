<?php
/*
 * Компонент ClientForm
 * занимается обработкой данных сессии и cookies
 * и передачей результата по запросу контроллера форм.
 * Также выполняет команды контроллера по обработке форм.
 */

/**
 * Class ClientFormComponent
 *
 */
class ClientFormComponent
{
	const SITE1 = 'main';
	const SITE2 = 'ivanovo';
	const FAST_REG = 'fast';
	const CONTINUE_REG = 'continue'; //продолжение регистрации

	private $iClientId;
	private $iCurrentStep;
	private $iDoneSteps;

	public $bFlagCodesSent; // флаг "коды отправлены"
	public $sSentPhone; // номер, на который отправлен код
	public $sEmailSentAddress; // адрес, на который отправлен код

	/**
	 * Массив свойств для виджета сквозной выбор продукта
	 * Этот же массив определяет, где хранится информация о выбранном продукте,
	 * и если на каком-то шаге информация не будет найдена - будет сделан сброс шагов
	 * TODO протестить для быстрой регистрации
	 *
	 * @var array
	 */
	public static $aSelectProductSettings = array(
		self::FAST_REG     => array(
			'view'  => 'main',
			'model' => 'ClientKreddyLineSelectProductForm',
		),
		self::CONTINUE_REG => array(
			'view'  => 'main',
			'model' => 'ClientSelectProductForm',
		),
		self::SITE1        => array(
			'view'  => 'main',
			'model' => 'ClientFastRegForm',
		),
		self::SITE2        => array(
			'view'  => 'flexible',
			'model' => 'ClientFlexibleProductForm',
		),
	);

	public static $aSelectChannelSettings = array(
		self::FAST_REG     => array(
			'view'  => 'main',
			'model' => 'ClientKreddyLineSelectChannelForm',
		),
		self::CONTINUE_REG => array(
			'view'  => 'main',
			'model' => 'ClientSelectProductForm',
		),
		self::SITE1        => array(
			'view'  => 'main',
			'model' => 'ClientFastRegForm',
		),
		self::SITE2        => array(
			'view'  => 'flexible',
			'model' => 'ClientFlexibleProductForm',
		),
	);

	public static $aSelectPayTypeSettings = array(
		self::FAST_REG     => array(
			'view'  => 'main',
			'model' => 'ClientKreddyLineSelectPayTypeForm',
		),
		self::CONTINUE_REG => array(
			'view'  => 'main',
			'model' => 'ClientSelectProductForm',
		),
		self::SITE1        => array(
			'view'  => 'main',
			'model' => 'ClientFastRegForm',
		),
		self::SITE2        => array(
			'view'  => 'flexible',
			'model' => 'ClientFlexibleProductForm',
		),
	);

	public static $aPhoneFormSettings = array(
		self::FAST_REG     => array(
			'model' => 'ClientKreddyLineRegForm',
		),
		self::CONTINUE_REG => array(
			'model' => 'ClientPersonalDataContinueForm',
		),
		self::SITE1        => array(
			'model' => 'ClientPersonalDataForm',
		),
		self::SITE2        => array(
			'model' => 'ClientPersonalDataForm',
		),
	);

	public static $aSuccessYmGoal = array(
		self::SITE1        => 'register_complete',
		self::SITE2        => 'register_complete',
		self::FAST_REG     => 'fr_register_complete',
		self::CONTINUE_REG => 'fr_continue_complete',
	);

	//TODO брать эти данные из массивы выше ^^^
	//имя моделей, в которох сохраняется телефон клиента
	private static $aPhoneForms = array(
		'ClientPersonalDataForm',
		'ClientFastRegForm',
		'ClientKreddyLineRegForm',
	);


	/**
	 * Конфигурация шагов заполнения анкеты:
	 * максимальный шаг, минимальный шаг, шаг по-умолчанию (на него переводит в случае, если текущий шаг вне пределов
	 * минимума-максимума)
	 *
	 * @var array
	 */

	public static $aSteps = array(
		self::FAST_REG     => array(
			'max' => 4,
			'min'     => 0,
			'default' => 0,
		),
		self::CONTINUE_REG => array(
			'max'     => 5,
			'min'     => 0,
			'default' => 0,
		),
		self::SITE1        => array(
			'max'     => 7,
			'min'     => 0,
			'default' => 0,
		),
		self::SITE2        => array(
			'max'     => 6,
			'min'     => 0,
			'default' => 0,
		),

	);

	public static $aFormWidgetSteps = array(
		self::SITE1        => array(
			2 => array(
				'form_step' => 1,
				'label'     => 'Личные данные',
				'url'       => '/form/ajaxForm/3'
			),
			3 => array(
				'form_step' => 2,
				'label'     => 'Паспортные данные',
				'url'       => '/form/ajaxForm/4'
			),
			4 => array(
				'form_step' => 3,
				'label'     => 'Постоянная регистрация',
				'url'       => '/form/ajaxForm/5'
			),
			5 => array(
				'form_step' => 4,
				'label'     => 'Дополнительно',
				'url'       => '/form/ajaxForm/6'
			),
			6 => array(
				'form_step' => 5,
				'label'     => 'Отправка заявки',
				'url'       => '/form/ajaxForm/7'
			),
		),
		self::CONTINUE_REG => array(
			0 => array(
				'form_step' => 1,
				'label'     => 'Личные данные',
				'url'       => '/form/ajaxForm/1'
			),
			1 => array(
				'form_step' => 2,
				'label'     => 'Паспортные данные',
				'url'       => '/form/ajaxForm/2'
			),
			2 => array(
				'form_step' => 3,
				'label'     => 'Постоянная регистрация',
				'url'       => '/form/ajaxForm/3'
			),
			3 => array(
				'form_step' => 4,
				'label'     => 'Дополнительно',
				'url'       => '/form/ajaxForm/5'
			),
			4 => array(
				'form_step' => 5,
				'label'     => 'Отправка заявки',
				'url'       => '/form/ajaxForm/6'
			),
		),
		self::SITE2        => array(
			1 => array(
				'form_step' => 1,
				'label'     => 'Личные данные',
				'url'       => '/form/ajaxForm/2'
			),
			2 => array(
				'form_step' => 2,
				'label'     => 'Паспортные данные',
				'url'       => '/form/ajaxForm/3'
			),
			3 => array(
				'form_step' => 3,
				'label'     => 'Постоянная регистрация',
				'url'       => '/form/ajaxForm/4'
			),
			4 => array(
				'form_step' => 4,
				'label'     => 'Дополнительно',
				'url'       => '/form/ajaxForm/5'
			),
			5 => array(
				'form_step' => 5,
				'label'     => 'Отправка заявки',
				'url'       => '/form/ajaxForm/6'
			),
		)
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
	 * go_next_step - по получении post-запроса на этом шаге следует сразу перейти к следующему шагу, модель не требуется
	 *
	 * breadcrumbs_step - шаг, отображаемый в breadscrumb
	 * metrika_goal - цель яндекс.метрики
	 *
	 * @var array
	 */

	private static $aStepsInfo = array(
		self::FAST_REG     => array(
			0 => array(
				'layout'           => '//layouts/main_kreddyline',
				'view'             => 'kreddyline',
				'sub_view'         => 'kreddyline/sum',
				'model' => 'ClientKreddyLineSelectProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
			),
			1 => array(
				'layout'           => '//layouts/main_kreddyline',
				'view'             => 'kreddyline',
				'sub_view'         => 'kreddyline/pay',
				'model' => 'ClientKreddyLineSelectPayTypeForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
			),
			2 => array(
				'layout'           => '//layouts/main_kreddyline',
				'view'             => 'kreddyline',
				'sub_view'         => 'kreddyline/channel',
				'model' => 'ClientKreddyLineSelectChannelForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
			),
			3 => array(
				'layout'           => '//layouts/main_kreddyline',
				'view'             => 'kreddyline',
				'sub_view'         => 'kreddyline/submit',
				'model' => 'ClientKreddyLineRegForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
			),
			4 => array(
				'view'             => 'client_fast_form',
				'sub_view'         => array(
					'condition' => 'getFlagCodesSent',
					true        => 'confirm_phone/check_sms_code_fast_reg',
					false       => 'confirm_phone/send_sms_code',
				),
				'model'            => 'ClientConfirmPhoneAndEmailForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => array(
					'condition' => 'getFlagCodesSent',
					true        => 'fr_sms_code_check',
					false       => 'fr_sms_code_send',
				),
				'topPageWidget'    => true,
			),
		),
		self::CONTINUE_REG => array(
			0 => array(
				'view'             => 'client_continue_form',
				'sub_view'         => 'steps/personal_data_continue',
				'model'            => 'ClientPersonalDataContinueForm',
				'modelDbRelations' => array(
					'phone',
					'first_name',
					'last_name',
					'third_name',
					'email',
				),
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'fr_personal_data',
			),
			1 => array(
				'view'             => 'client_continue_form',
				'sub_view'         => 'steps/passport_data',
				'model'            => 'ClientPassportDataForm',
				'modelDbRelations' => array(
					'birthday'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'fr_passport_data',
			),
			2 => array(
				'view'             => 'client_continue_form',
				'sub_view'         => 'steps/address_data',
				'model'            => 'ClientAddressDataForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'fr_address_data',
			),
			3 => array(
				'view'             => 'client_continue_form',
				'sub_view'         => 'steps/job_data',
				'model'            => 'ClientJobDataForm',
				'modelDbRelations' => array(
					'phone'
				),
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'fr_job_data',
			),
			4 => array(
				'view'             => 'client_continue_form',
				'sub_view'         => 'steps/secret_data_continue',
				'model'            => 'ClientSecretDataContinueForm',
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'fr_secret_data',
			),
			5 => array(
				'controllerMethod' => 'continueRegSuccess', //на этом шаге требуется вызвать метод, и больше ничего не нужно делать
			),
		),
		self::SITE1        => array(
			0 => array(
				'layout'           => '//layouts/main_kreddyline',
				'view'             => 'kreddyline',
				'sub_view'         => 'kreddyline/sum',
				'model' => 'ClientKreddyLineSelectProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
			),
			1 => array(
				'view'             => 'infographic',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'infographic',
				'go_next_step'     => true,
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
				'breadcrumbs_step' => 2,
				'metrika_goal'     => 'address_data',
			),
			5 => array(
				'view'             => 'client_form',
				'sub_view'         => 'steps/job_data',
				'model'            => 'ClientJobDataForm',
				'modelDbRelations' => array(
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
					'condition' => 'getFlagCodesSent',
					true        => 'confirm_phone/check_sms_code',
					false       => 'confirm_phone/send_sms_code',
				),
				'model'            => 'ClientConfirmPhoneAndEmailForm',
				'breadcrumbs_step' => 3,
				'metrika_goal'     => array(
					'condition' => 'getFlagCodesSent',
					true        => 'sms_code_check',
					false       => 'sms_code_send',
				)
			),
		),
		self::SITE2        => array(
			0 => array(
				'view'             => 'client_flexible_product',
				'model'            => 'ClientFlexibleProductForm',
				'breadcrumbs_step' => 1,
				'metrika_goal'     => 'select_product',
				'topPageWidget'    => true,
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
					'condition' => 'getFlagCodesSent',
					true        => 'confirm_phone/check_sms_code',
					false       => 'confirm_phone/send_sms_code',
				),
				'model'            => 'ClientConfirmPhoneAndEmailForm',
				'breadcrumbs_step' => 3,
				'metrika_goal'     => array(
					'condition' => 'getFlagCodesSent',
					true        => 'sms_code_check',
					false       => 'sms_code_send',
				)
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

		if (in_array(get_class($oClientForm), self::$aPhoneForms)) {
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
		if (in_array(get_class($oClientForm), self::$aPhoneForms)) {
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
				if (empty($aClientFormData['pay_type'])) {
					$aClientFormData['pay_type'] = $this->getSessionPayType();
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
			 * если номер/адрес, на который отправлялось СМС/email, не совпадает с введенным,
			 * т.е. клиент вернулся на анкету и ввел другой номер/адрес,
			 * то позволяем снова отправить СМС/email с кодом подтверждения
			 */

			if (!$this->compareThisAndSentPhones($oClientForm['phone']) || !$this->compareThisAndSentEmails($oClientForm['email'])) {
				$this->setFlagCodesSent(false);
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
	 * @param bool $bSmsSend
	 * @param bool $bEmailSend
	 *
	 * @param bool $bResend
	 *
	 * @return bool|string
	 */
	public function sendCodes($bSmsSend = true, $bEmailSend = true, $bResend = false)
	{
		// если с данного ip нельзя запросить SMS, выдаём ошибку
		if (!Yii::app()->antiBot->checkSmsRequest()) {
			return Dictionaries::C_ERR_GENERAL;
		}

		$iClientId = $this->getClientId();
		$aClientForm = ClientData::getClientDataById($iClientId);

		$bSessionAndSentPhonesEquals = $this->compareSessionAndSentPhones();
		$bSessionAndSentEmailsEquals = $this->compareSessionAndSentEmails();

		// если не затребована переотправка, и если коды уже есть в базе, и номер телефона и email не менялись в форме,ставим флаг и вовзращаем true
		if (!$bResend
			&& !empty($aClientForm['sms_code'])
			&& $bSessionAndSentPhonesEquals
			&& !empty($aClientForm['email_code'])
			&& $bSessionAndSentEmailsEquals
		) {
			$this->setFlagCodesSent(true);

			return true;
		}

		$sPhone = $this->getSessionPhone();
		$sEmail = $this->getSessionEmail();

		// если в сессии нет телефона, ошибка - некуда отправлять
		if (empty($sPhone) || empty($sEmail)) {
			return Dictionaries::C_ERR_CODE_CANT_SEND;
		}

		$bSmsSentOk = true;
		// если номер в сессии (форма) не равен номеру, на который уже отправили СМС (если отправили)
		// или требуется переотправка
		// и при этом запрошена отправка SMS
		if (
			(!$bSessionAndSentPhonesEquals || $bResend)
			&& $bSmsSend
		) {
			$sSmsCode = $aClientForm['sms_code']; // запишем старый код из БД
			// старый код используем в случае переотправки, чтобы не получилось так, что клиенту дошла старая СМС с кодом
			// и клиент пробует его ввести, а уже запрошен новый код - и СМС с ним еще не пришла
			// если не запрошена переотправка, то генерируем новый код
			if (!$bResend) {
				$sSmsCode = $this->generateSMSCode(SiteParams::C_SMS_CODE_LENGTH);
			}
			//отправляем СМС
			$sMessage = "Ваш код подтверждения: " . $sSmsCode;
			//отправляем СМС через API
			$bSmsSentOk = Yii::app()->adminKreddyApi->sendSms($sPhone, $sMessage);
			$aClientForm['sms_code'] = $sSmsCode;
		}

		$bEmailSentOk = true;
		// если адрес в сессии (форма) не равен адресу, на который уже отправили письмо (если отправили)
		if (
			(!$bSessionAndSentEmailsEquals || $bResend)
			&& $bEmailSend
		) {
			$sEmailCode = $aClientForm['email_code']; // запишем старый код из БД
			// старый код используем в случае переотправки, чтобы не получилось так, что клиенту дошел старый e-mail с кодом
			// и клиент пробует его ввести, а уже запрошен новый код - и e-mail с ним еще не пришел
			// если не запрошена переотправка, то генерируем новый код
			if (!$bResend) {
				$sEmailCode = $this->generateSMSCode(SiteParams::C_EMAIL_CODE_LENGTH);
			}
			$bEmailSentOk = Yii::app()->adminKreddyApi->sendEmailCode($sEmail, $sEmailCode);
			$aClientForm['email_code'] = $sEmailCode;
		}

		if ($bSmsSentOk && $bEmailSentOk) {
			//добавляем в лог запрос sms с этого ip
			Yii::app()->antiBot->addSmsRequest();
			$this->setSmsSentPhone($sPhone); //записываем, на какой номер было отправлено СМС
			$this->setEmailSentAddress($sEmail); // записываем, на какой адрес было отправлено письмо

			// если не удалось записать в БД - общая ошибка
			if (!ClientData::saveClientDataById($aClientForm, $iClientId)) {
				return Dictionaries::C_ERR_GENERAL;
			}

			// ставим флаг успешной отправки
			$this->setFlagCodesSent(true);

			// возвращаем true
			return true;
		}

		return Dictionaries::C_ERR_CODE_CANT_SEND;
	}


	/**
	 * Сверяет код с кодом из базы
	 *
	 * @param array $aPostData
	 *
	 * @param       $oCheckResult
	 *
	 * @return string|integer
	 */
	public function checkCodes($aPostData, &$oCheckResult)
	{
		$iClientId = $this->getClientId();

		$oClientSmsForm = new ClientConfirmPhoneAndEmailForm();
		$oClientSmsForm->setAttributes($aPostData);

		// если число попыток ввода кода меньше максимально допустимых
		if (!$this->isSmsCodeTriesExceed()) {
			// если введённые данные валидны и совпадают с кодом из базы
			$bSmsCodeIsCorrect = false;
			$bEmailCodeIsCorrect = false;

			if ($oClientSmsForm->validate()) {
				$bSmsCodeIsCorrect = ClientData::compareSMSCodeByClientId($oClientSmsForm->sms_code, $iClientId);
				$bEmailCodeIsCorrect = ClientData::compareEmailCodeByClientId($oClientSmsForm->email_code, $iClientId);
			}
			if (
				$bSmsCodeIsCorrect
				&& $bEmailCodeIsCorrect
			) {
				// подтверждение по SMS выполнено успешно. помечаем запись в базе, очищаем сессию и выводим сообщение
				$aData['flag_sms_confirmed'] = 1;
				ClientData::saveClientDataById($aData, $iClientId);

				// успешная проверка
				return true;
			} else {
				$oCheckResult->bEmailError = !$bEmailCodeIsCorrect;
				$oCheckResult->bSmsError = !$bSmsCodeIsCorrect;

				$iSmsCountTries = $this->getSmsCountTries();
				$iSmsCountTries += 1;
				$this->setSmsCountTries($iSmsCountTries);

				// если это была последняя попытка
				if ($iSmsCountTries == SiteParams::MAX_SMSCODE_TRIES) {
					// возвращаем сообщение о превышении числа попыток
					return Dictionaries::C_ERR_CODE_TRIES;
				} else {
					// выводим сообщение - код неверен + сколько осталось попыток
					$iTriesLeft = SiteParams::MAX_SMSCODE_TRIES - $iSmsCountTries;

					return (Dictionaries::C_ERR_CODE_WRONG . ' ' . Dictionaries::C_ERR_TRIES_LEFT . $iTriesLeft);
				}
			}
		} else {
			// возвращаем сообщение о превышении числа попыток
			return Dictionaries::C_ERR_CODE_TRIES;
		}
	}

	/**
	 * @return bool
	 */
	public function isSmsCodeTriesExceed()
	{
		$iSmsCountTries = $this->getSmsCountTries();

		if ($iSmsCountTries < SiteParams::MAX_SMSCODE_TRIES) {
			return false;
		}

		return true;
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
		$sSite = $this->getSiteConfigName();

		$aClientData['site_region'] = $this->getSiteRegionId();

		//если клиент прошел быструю регистрацию, то отправляем его другим методом
		if ($sSite == self::FAST_REG) {

			return Yii::app()->adminKreddyApi->createFastRegClient($aClientData);
		}

		return Yii::app()->adminKreddyApi->createClient($aClientData);
	}

	/**
	 * Получить регион клиента из cookie
	 *
	 * @return string
	 */
	public function getSiteRegionId()
	{
		$sCityAndRegion = Yii::app()->request->cookies['cityAndRegion'];
		$sRegion = '';
		if (!empty($sCityAndRegion)) {
			$aLocation = explode(',', $sCityAndRegion);
			if (count($aLocation) == 2) {
				$sRegion = trim($aLocation['1']);
			} else {
				$sRegion = trim($aLocation['0']);
			}
		}
		$iRegion = CitiesRegions::getRegionIdByName($sRegion);

		return $iRegion;
	}

	/**
	 * @param $aClientData
	 *
	 * @return bool
	 */
	public function updateFastRegClient($aClientData)
	{
		if ($this->isContinueReg()) {
			return Yii::app()->adminKreddyApi->updateFastRegClient($aClientData);
		}

		return false;

	}


	public function resetSteps()
	{
		$iDefaultStep = $this->getDefaultStep();
		$this->setCurrentStep($iDefaultStep);
		$this->setDoneSteps($iDefaultStep);
	}

	/**
	 * @return array
	 */
	public function getMetrikaGoalByStep()
	{
		$sSite = self::getSiteConfigName();

		$mGoal = self::$aStepsInfo[$sSite][$this->iCurrentStep]['metrika_goal'];

		if (is_array($mGoal) && isset($mGoal['condition'])) {
			$bCondition = $this->$mGoal['condition']();
			$sGoal = $mGoal[$bCondition];
		} else {
			$sGoal = $mGoal;
		}

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
		$sSite = $this->getSiteConfigName();

		return isset(self::$aSteps[$sSite]['default']) ? self::$aSteps[$sSite]['default'] : 0;

	}

	/**
	 * @return int
	 */
	public function getMaxStep()
	{
		$sSite = $this->getSiteConfigName();

		return isset(self::$aSteps[$sSite]['max']) ? self::$aSteps[$sSite]['max'] : 0;

	}

	/**
	 * @return int
	 */
	public function getMinStep()
	{
		$sSite = $this->getSiteConfigName();

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

		$sSite = self::getSiteConfigName();

		$sModel = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['model'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['model']
			: null;

		//создаем модель
		if ($sModel) {
			$oModel = new $sModel();
		} else {
			return null;
		}

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
		$sSite = self::getSiteConfigName();

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
	 * Возвращает лэйаут для текущего шага, если он задан
	 *
	 * @return null
	 */
	public function getLayout()
	{
		$sSite = self::getSiteConfigName();

		$sLayout = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['layout'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['layout']
			: null;

		return $sLayout;
	}

	/**
	 * Возвращает массив отправленных данных, если был выполнен POST-запрос, либо null.
	 *
	 * @return array|null
	 */
	public function getPostData()
	{
		$sSite = self::getSiteConfigName();

		$sModel = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['model'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['model']
			: false;


		return Yii::app()->request->getParam($sModel);
	}

	/**
	 * @return string
	 */
	public static function getSelectProductView()
	{
		$sSite = self::getSiteConfigName();
		$sView = isset(self::$aSelectProductSettings[$sSite]['view']) ? self::$aSelectProductSettings[$sSite]['view'] : '';

		return $sView;
	}

	/**
	 * @return string
	 */
	public static function getSelectProductModelName()
	{
		$sSite = self::getSiteConfigName();
		$sModelName = isset(self::$aSelectProductSettings[$sSite]['model']) ? self::$aSelectProductSettings[$sSite]['model'] : '';

		return $sModelName;
	}

	/**
	 * @return string
	 */
	public static function getSiteConfigName()
	{
		$sSite = Yii::app()->session['site_config'];

		//если текущий конфиг "продолжение регистрации"
		if ($sSite == self::CONTINUE_REG) {
			//проверим, залогинен ли юзер
			if (Yii::app()->user->getId() && Yii::app()->adminKreddyApi->isLoggedIn()) {
				return self::CONTINUE_REG;
			}
			//если не залогинен, то сбрасываем конфиг сайта на дефолтный
			Yii::app()->session['site_config'] = null;
			$sSite = null;
		}

		//если есть текущий конфиг в сессии, то вернем его
		if ($sSite) {
			return $sSite;
		}

		return (SiteParams::getIsIvanovoSite())
			? self::SITE2
			: self::SITE1;
	}

	/**
	 * @param $cSiteConfig
	 */
	public function setSiteConfigName($cSiteConfig)
	{
		Yii::app()->session['site_config'] = $cSiteConfig;
	}

	/**
	 * Переводит обработку форм на следующий шаг
	 *
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
		$sSite = $this->getSiteConfigName();

		//если у нас продолжение регистрации - то телефон берем из userId
		if ($sSite == self::CONTINUE_REG) {
			return Yii::app()->user->getId();
		}

		$sModel = self::$aPhoneFormSettings[$sSite]['model'];

		if (isset(Yii::app()->session[$sModel]['phone'])) {
			$sPhone = Yii::app()->session[$sModel]['phone'];
		} else {
			$sPhone = false;
		}

		return $sPhone;
	}

	/**
	 * @return bool|string
	 */
	public function getSessionEmail()
	{
		$sSite = $this->getSiteConfigName();

		//если у нас продолжение регистрации - то телефон берем из userId
		if ($sSite == self::CONTINUE_REG) {
			return Yii::app()->user->getId();
		}

		$sModel = self::$aPhoneFormSettings[$sSite]['model'];

		if (isset(Yii::app()->session[$sModel]['email'])) {
			$sEmail = Yii::app()->session[$sModel]['email'];
		} else {
			$sEmail = false;
		}

		return $sEmail;
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
		$sSite = $this->getSiteConfigName();

		$sModel = self::$aSelectProductSettings[$sSite]['model'];

		return isset(Yii::app()->session[$sModel]['product'])
			? Yii::app()->session[$sModel]['product']
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
	 *
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
		$sSite = $this->getSiteConfigName();

		$sModel = self::$aSelectChannelSettings[$sSite]['model'];

		return isset(Yii::app()->session[$sModel]['channel_id'])
			? Yii::app()->session[$sModel]['channel_id']
			: false;
	}

	/**
	 * @return string|bool
	 */
	public function getSessionPayType()
	{
		$sSite = $this->getSiteConfigName();

		$sModel = self::$aSelectPayTypeSettings[$sSite]['model'];

		return isset(Yii::app()->session[$sModel]['pay_type'])
			? Yii::app()->session[$sModel]['pay_type']
			: false;
	}

	/**
	 * @return int
	 */
	public function getSmsCountTries()
	{
		return (isset(Yii::app()->session['ClientConfirmPhoneAndEmailForm']['smsCountTries'])) ? Yii::app()->session['ClientConfirmPhoneAndEmailForm']['smsCountTries'] : 0;
	}

	/**
	 * @param int $iSmsCountTries
	 */
	public function setSmsCountTries($iSmsCountTries)
	{
		$array = Yii::app()->session['ClientConfirmPhoneAndEmailForm'];
		$array['smsCountTries'] = $iSmsCountTries;
		Yii::app()->session['ClientConfirmPhoneAndEmailForm'] = $array;
	}

	/**
	 * @return bool
	 */
	public function getFlagCodesSent()
	{
		if (isset($this->bFlagCodesSent)) {
			return $this->bFlagCodesSent;
		}

		return (!empty(Yii::app()->session['ClientConfirmPhoneAndEmailForm']['flagCodesSent']));
	}

	/**
	 * @param $bFlagCodesSent
	 *
	 */
	public function setFlagCodesSent($bFlagCodesSent)
	{
		$this->bFlagCodesSent = $bFlagCodesSent;
		$array = Yii::app()->session['ClientConfirmPhoneAndEmailForm'];
		$array['flagCodesSent'] = $bFlagCodesSent;
		Yii::app()->session['ClientConfirmPhoneAndEmailForm'] = $array;
	}

	/**
	 * @param $sPhone
	 */
	public function setSmsSentPhone($sPhone)
	{
		$this->sSentPhone = $sPhone;
		Yii::app()->session['sSmsSentPhone'] = $sPhone;
	}

	/**
	 * @return string
	 */
	public function getSmsSentPhone()
	{
		if (isset($this->sSentPhone)) {
			return $this->sSentPhone;
		}

		return Yii::app()->session['sSmsSentPhone'];
	}

	/**
	 * @param $sEmail
	 */
	public function setEmailSentAddress($sEmail)
	{
		$this->sEmailSentAddress = $sEmail;
		Yii::app()->session['sEmailSentAddress'] = $sEmail;
	}

	/**
	 * @return string
	 */
	public function getEmailSentAddress()
	{
		if (isset($this->sEmailSentAddress)) {
			return $this->sEmailSentAddress;
		}

		return Yii::app()->session['sEmailSentAddress'];
	}

	public function clearClientSession()
	{
		//сбрасываем шаги заполнения анкеты
		$this->resetSteps();

		//удаляем идентификаторы
		Yii::app()->session['client_id'] = null;
		Yii::app()->session['tmp_client_id'] = null;

		$sSite = $this->getSiteConfigName();

		//чистим данные форм
		foreach (self::$aStepsInfo[$sSite] as $aStep) {
			if (isset($aStep['model'])) {
				Yii::app()->session[$aStep['model']] = null;
			}
		}

		//удаляем данные из куки
		$aCookieData = array('client_id' => null, 'phone' => null);
		Cookie::saveDataToCookie('client', $aCookieData);
	}

	/**
	 * Устанавливает для каждого шага сессию с данными
	 *
	 * @param $aClientData
	 * @param $iClientId
	 */
	public function setFastRegClientSession($aClientData, $iClientId)
	{
		$sSite = $this->getSiteConfigName();

		//заполяем данные сессии для каждой формы
		foreach (self::$aStepsInfo[$sSite] as $aStep) {
			//var_dump($aStep);
			if (isset($aStep['model'])) {
				/**
				 * @var ClientCreateFormAbstract $oForm ;
				 */
				$oForm = new $aStep['model'];
				$oForm->setAttributes($aClientData, false);
				Yii::app()->session[$aStep['model']] = $oForm->getAttributes();
				Yii::app()->session[$aStep['model'] . '_client_id'] = $iClientId;
			}

		}
	}

	/**
	 * @param $iLength
	 *
	 * @return string
	 */
	private function generateSMSCode($iLength = SiteParams::C_SMS_CODE_LENGTH)
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
	 * @return bool
	 */
	public function compareSessionAndSentEmails()
	{
		return (Yii::app()->clientForm->getEmailSentAddress() === Yii::app()->clientForm->getSessionEmail());
	}

	/**
	 * @param $sPhone
	 *
	 * @return bool
	 */
	public function compareThisAndSentPhones($sPhone)
	{
		return (Yii::app()->clientForm->getSmsSentPhone() === $sPhone);
	}

	/**
	 * @param $sEmailAddress
	 *
	 * @return bool
	 */
	public function compareThisAndSentEmails($sEmailAddress)
	{
		return (Yii::app()->clientForm->getEmailSentAddress() === $sEmailAddress);
	}

	/**
	 * Метод для получения breadcrumbs для страниц формы
	 *
	 * @return array
	 */
	public function getBreadCrumbs()
	{
		if ($this->isContinueReg()) {
			return SiteParams::$aContinueRegBreadCrumbs;
		}

		if (SiteParams::getIsIvanovoSite()) {
			return SiteParams::$aIvanovoBreadCrumbs;
		}

		return SiteParams::$aMainBreadCrumbs;
	}

	/**
	 * @return int|null
	 */
	public function getBreadCrumbsStep()
	{
		$sSite = self::getSiteConfigName();

		$iBreadCrumbsStep = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['breadcrumbs_step'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['breadcrumbs_step']
			: null;

		return $iBreadCrumbsStep;

	}

	/**
	 * Проверяем, нужно ли на текущем шаге сразу переходить к следующему шагу, не проводя обработку переданных данных.
	 * Требуется для "пустых" страниц без форм, содержащих, например, рекламные сообщения или различную информацию.
	 *
	 * @return bool
	 */
	public function tryGoNextStep()
	{
		$sSite = Yii::app()->clientForm->getSiteConfigName();

		if (
			Yii::app()->request->isPostRequest
			&& !empty(self::$aStepsInfo[$sSite][$this->iCurrentStep]['go_next_step'])
		) {
			$this->nextStep();

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function checkSiteSelectedProduct()
	{
		$sSite = $this->getSiteConfigName();

		$sModel = self::$aSelectProductSettings[$sSite]['model'];

		$bIsSelectedProduct = !empty(Yii::app()->session[$sModel]);

		//если не выбран продукт, и это не продолжение регистрации (там продукт не выбираем) - то сбрасываем шаги
		if (!$bIsSelectedProduct && !$this->isContinueReg()) {
			$this->resetSteps();
		}
	}

	public function getFormWidgetSteps()
	{
		return self::$aFormWidgetSteps[$this->getSiteConfigName()];
	}

	/**
	 * @return bool
	 */
	public function isTopPageWidgetVisible()
	{
		$sSite = $this->getSiteConfigName();
		$iCurrentStep = $this->getCurrentStep();

		return !empty(self::$aStepsInfo[$sSite][$iCurrentStep]['topPageWidget']);
	}

	/**
	 * @return mixed
	 */
	public function getError()
	{
		$sError = Yii::app()->session['error'];
		Yii::app()->session['error'] = null;

		return $sError;
	}

	/**
	 * @return bool
	 */
	public function hasError()
	{
		return !empty(Yii::app()->session['error']);
	}

	public function checkRegistrationType()
	{
		$iCurrentStep = $this->getCurrentStep();
		//если текущий шаг нулевой, значит надо сделать выбор типа регистрации
		//потому если не нулевой - то выход
		if ($iCurrentStep != 0) {
			return;
		}

		//получаем модель быстрой регистрации
		$sModel = self::$aStepsInfo[self::FAST_REG][$iCurrentStep]['model'];
		//создаем объект формы
		$oClientFastRegForm = new $sModel();
		//получаем данные из запроса
		$aPost = Yii::app()->request->getPost(get_class($oClientFastRegForm));

		//если был запрос с такой формой, и флаг fast_reg установлен, переключаем режим и выходим
		if (isset($aPost) && !empty($aPost['fast_reg'])) {
			//переключаем режим сайта на быструю регистрацию
			Yii::app()->clientForm->setSiteConfigName(ClientFormComponent::FAST_REG);

			return;
		}

		$sSite = $this->getSiteConfigName();

		//если текущая конфигурация "быстрая регистрация", но данных не пришло или пришли без флага, то нужно переключить в обычный режим
		if ($sSite == self::FAST_REG) {
			//сбрасываем конфигурацию регистрации, чтобы задействовалась конфигурация по-умолчанию
			$this->setSiteConfigName(null);
		}
	}

	/**
	 * Параметр продолжения регистрации после быстрой регистрации и перехода из ЛК на заполнение анкеты
	 *
	 * @param $bContinue
	 */
	public function setContinueReg($bContinue)
	{
		if ($bContinue) {
			$this->setSiteConfigName(self::CONTINUE_REG);
		} else {
			$this->setSiteConfigName(null);
		}
	}

	/**
	 * @return bool
	 */
	public function isContinueReg()
	{
		$sSite = $this->getSiteConfigName();

		return ($sSite == self::CONTINUE_REG);
	}

	/**
	 * сохраняем данные перед редиректом в ЛК
	 */
	public function saveDataBeforeRedirectToAccount($aClientData)
	{
		if (!empty($aClientData['product'])) {
			Yii::app()->user->setState('product', $aClientData['product']);
		}
		if (!empty($aClientData['channel_id'])) {
			Yii::app()->user->setState('channel_id', $aClientData['channel_id']);
		}
		if (!empty($aClientData['pay_type'])) {
			Yii::app()->user->setState('pay_type', $aClientData['pay_type']);
		}
		if (!empty($aClientData['flex_amount'])) {
			Yii::app()->user->setState('flex_amount', $aClientData['flex_amount']);
		}
		if (!empty($aClientData['flex_time'])) {
			Yii::app()->user->setState('flex_time', $aClientData['flex_time']);
		}
		Yii::app()->user->setState('new_client', true);

	}

	/**
	 * Получаем указанный в настройках метод контроллера, который требуется выполнить на текущем шаге
	 *
	 * @return null
	 */
	public function getControllerMethod()
	{
		$sSite = self::getSiteConfigName();

		$sControllerMethod = isset(self::$aStepsInfo[$sSite][$this->iCurrentStep]['controllerMethod'])
			? self::$aStepsInfo[$sSite][$this->iCurrentStep]['controllerMethod']
			: null;

		return $sControllerMethod;
	}

	/**
	 * Проверяем, что находимся на последнем шаге формы, значит форма заполнена
	 *
	 * @return bool
	 */
	public function checkFormComplete()
	{
		$iCurrentStep = $this->getCurrentStep();

		$sSite = self::getSiteConfigName();
		$iLastStep = self::$aSteps[$sSite]['max'];

		return $iCurrentStep == $iLastStep;
	}

	/**
	 * @param $oClientForm
	 *
	 * @return null|string
	 */
	public function doAjaxValidation($oClientForm)
	{
		$sAjaxClass = Yii::app()->request->getParam('ajax');
		//если для ajax-валидации пришла форма, соответствующая текущему шагу, то обработаем ее
		if (
			$sAjaxClass == get_class($oClientForm) ||
			$sAjaxClass == get_class($oClientForm) . '_fast'
			//для формы быстрой регистрации
		) {
			$sEcho = IkTbActiveForm::validate($oClientForm); //проводим валидацию и возвращаем результат
			Yii::app()->clientForm->saveAjaxData($oClientForm); //сохраняем полученные при ajax-запросе данные
			return $sEcho;
		}

		return null;
	}

	/**
	 * @param bool $bComplete
	 */
	public function setRegisterComplete($bComplete = true)
	{
		if ($bComplete == false) {
			Yii::app()->session['register_complete'] = null;

			return;
		}

		$sSite = self::getSiteConfigName();

		$sYmGoal = self::$aSuccessYmGoal[$sSite];

		Yii::app()->session['register_complete'] = array(
			'sRegSite' => $sSite,
			'sYmGoal'  => $sYmGoal
		);
	}

	/**
	 * @return mixed
	 */
	public function getRegisterComplete()
	{
		return Yii::app()->session['register_complete'];
	}

}
