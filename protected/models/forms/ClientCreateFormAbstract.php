<?php
/*
 * Выбор суммы
 *
 * Выбор способа получения
 *
 * - 1 -
 * Контактные данные:
 * + Телефон
 * + Электронная почта
 * Личные данные:
 * + Фамилия
 * + Имя
 * + Отчество
 * + Дата рождения
 * + Пол
 * Паспортные данные:
 * + Серия / номер
 * + Когда выдан
 * -+ Кем выдан
 * + Код подразделения
 * Второй документ:
 * + Название
 * + Номер
 *
 * - 2 -
 * Адрес:
 * + Регион
 * + Город
 * + Адрес
 * Контактное лицо:
 * - ФИО
 * - Номер телефона
 *
 * - 3 -
 * Информация о работе:
 * - Место работы (название компании)
 * - Должность
 * - Номер телефона
 * - Стаж работы
 * - Среднемесячный доход
 * - Среднемесячный расход
 * - Наличие кредитов и займов в прошлом
 *
 * - 5 -
 * Подтверждение и отправка заявки
 * - Цифровой код
 * * Он потребуется для идентификации при получении займов через контактный центр "Кредди"
 * ** Должен содержать не менее 4 цифр
 * v Согласен с условиями и передачей данных
 *
 */
/**
 * Class ClientCreateFormAbstract
 * общий класс для всех форм ввода клиента
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientCreateFormAbstract extends CFormModel
{

	public $comment; //комментарий
	public $entry_point_id; //источник анкеты

	//>--- паспортные данные ---<//

	public $first_name; // имя
	public $last_name; // фамилия
	public $third_name; // отчество

	public $sex; // пол

	public $prev_last_name; // прежняя фамилия

	public $birthday; // день (дата) рождения

	public $passport_series; // серия паспорта
	public $passport_number; // номер паспорта
	public $passport_issued; // кем выдан
	public $passport_date; // когда выдан
	public $passport_code; // код подразделения @new

	//>--- Адрес регистрации ---<//

	public $address_reg_region; // Республика/край/область*
	public $address_reg_city; // Населенный пункт (Город, поселок, деревня и т.д.)*
	public $address_reg_address; // Адрес (Улица, дом, корпус/строение, квартира)*
	public $address_reg_post_index; // Индекс

	public $address_reg_as_res = 1; // признак совпадения адресов регистрации и места жительства

	public $address_res_region; // Республика/край/область*
	public $address_res_city; // Населенный пункт (Город, поселок, деревня и т.д.)*
	public $address_res_address; // Адрес (Улица, дом, корпус/строение, квартира)*
	public $address_res_post_index; // Индекс

	//>--- Контактные данные ---<//

	public $phone; // телефон
	public $phone_home; // домашний телефон
	public $email; // электронная почта

	//>--- Код для подтверждения повторных займов* ---<//

	public $numeric_code; // цифровой код
	public $secret_question; // секретный вопрос
	public $secret_answer; // ответ на секретный вопрос

	//>--- Второй документ* ---<//

	public $document; // второй документ
	public $document_number; // номер второго документа

	//>--- Информация о работе или (галочка) *Безработный (для коллекторов) ---<//

	public $job_less; // безработный
	public $job_company; // компания
	public $job_position; // должность
	public $job_phone; // рабочий телефон
	public $job_time; // стаж работы
	public $job_monthly_income; //месячный доход
	public $job_monthly_outcome; //месячный расход

	//>--- Наличие кредитной истории ---<//

	public $have_past_credit = 0; // раньше были кредиты @new

	//>--- Контактные данные ближайшего окружения ---<//

	public $relatives_one_fio; // обязательный один контакт - фио знакомого или родственника @new
	public $relatives_one_phone; // обязательный один телефон - к фио знакомого/родственника @new

	//>--- Контактные данные ближайшего окружения (для коллекторов)* ---<//

	public $friends_fio; // фио друга
	public $friends_phone; // телефон друга

	public $relatives_degree; // родственник: степень родства
	public $relatives_fio; // родственник: фио
	public $relatives_phone; // родственник: телефон

	public $job_contact_name; // коллега: фио
	public $job_contact_phone; // коллега: телефон

	//>--- Семейное положение (для коллекторов) ---<//

	public $marital_status; // семейное положение
	public $have_dependents = 0; // имеются иждевенцы

	//>--- Данные о доходах и активах (для коллекторов) ---<//

	public $job_salary_date; //в каких числах начисляется зарплата
	public $job_prepay_date; // в каких числах начисляют аванс
	public $job_income_add; // дополнительный доход

	//>--- Комментарии ---<//

	public $info_callcenter;
	public $info_stand;
	public $info_collector;
	public $info;

	/**
	 * получить правила для полей
	 * @param array $aFields
	 * @param array $aRequires
	 *
	 * @return array
	 */
	protected function getRulesByFields($aFields, $aRequires = array())
	{

		$aRules = array();

		if ($aRequires) {
			$aRules[] = array($aRequires, 'required');
		}

		foreach ($aFields as $sFieldName) {

			switch ($sFieldName) {
				case 'first_name':
				case 'prev_first_name':
					$aRules[] = array($sFieldName, 'checkValidClientName', 'message' => 'Имя может содержать только русские буквы');
					break;

				case 'last_name':
				case 'prev_last_name':
					$aRules[] = array($sFieldName, 'checkValidClientName', 'message' => 'Фамилия может содержать русские буквы, пробел и дефис');
					break;

				case 'third_name':
				case 'prev_third_name':
					$aRules[] = array($sFieldName, 'checkValidClientName', 'message' => 'Отчество может содержать только русские буквы');
					break;

				case 'relatives_one_fio':
				case 'friends_fio':
					$aRules[] = array($sFieldName, 'checkValidFio', 'message' => 'ФИО может содержать только русские буквы, пробелы и дефис');
					break;

				case 'sex':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Укажите пол', 'range' => array_keys(Dictionaries::$aSexes));
					break;

				case 'marital_status':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите семейное положение из списка', 'range' => array_keys(Dictionaries::$aMaritalStatuses));
					break;

				case 'document':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите документ из списка', 'range' => array_keys(Dictionaries::$aDocuments));
					break;

				case 'document_number':
					$aRules[] = array($sFieldName, 'checkValidDocumentNumber', 'chosenDocument' => 'document', 'messageEmptyDocument' => 'Сначала выберите тип документа');
					break;

				case 'education':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите образование из списка', 'range' => array_keys(Dictionaries::$aEducations));
					break;

				case 'birthday':
					$aRules[] = array($sFieldName, 'date', 'message' => 'Введите корректное значение для даты', 'format' => 'dd.MM.yyyy');
					$aRules[] = array($sFieldName, 'checkValidAge', 'message' => 'Введите корректное значение даты рождения');
					break;

				case 'passport_date':
					$aRules[] = array($sFieldName, 'date', 'message' => 'Введите корректное значение для даты', 'format' => 'dd.MM.yyyy');
					$aRules[] = array(
						$sFieldName, 'checkValidPassportDate', 'birthDate'            => 'birthday',
						                                       'message'              => 'Введите корректное значение даты выдачи паспорта',
						                                       'messageExpiredDate'   => 'Паспорт просрочен',
						                                       'messageEmptyBirthday' => 'Сначала введите корректное значение даты рождения',
					);
					break;

				case 'passport_series':
					$aRules[] = array($sFieldName, 'match', 'message' => 'Серия паспорта должна состоять из четырех цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_S_LENGTH . '}$/');
					break;

				case 'passport_number':
					$aRules[] = array($sFieldName, 'match', 'message' => 'Номер паспорта должен состоять из шести цифр', 'pattern' => '/^\d{' . SiteParams::C_PASSPORT_N_LENGTH . '}$/');
					break;

				case 'passport_code':
					$aRules[] = array($sFieldName, 'match', 'message' => 'Неверный формат кода, пример верного кода: 123-456', 'pattern' => '/^\d{3}\-\d{3}$/');
					break;

				case 'address_reg_post_index':
				case 'address_res_post_index':
					$aRules[] = array($sFieldName, 'match', 'message' => 'Почтовый индекс должен состоять из шести цифр', 'pattern' => '/^\d{' . SiteParams::C_POST_INDEX_LENGTH . '}$/');
					break;

				case 'passport_issued':
					$aRules[] = array($sFieldName, 'checkValidPassportIssued', 'message' => 'Поле может содержать только русские буквы, цифры, пробелы и знаки препинания');
					break;

				case 'address_reg_region':
				case 'address_reg_city':
				case 'address_reg_address':
					$aRules[] = array($sFieldName, 'checkValidAddressRegion', 'message' => 'Поле может содержать только русские буквы, цифры, пробелы и знаки препинания');
					break;

				case 'phone':
				case 'phone_home':
				case 'job_phone':
				case 'relatives_one_phone':
				case 'job_director_phone':
				case 'friends_phone':
					$aRules[] = array($sFieldName, 'checkValidClientPhone', 'message' => 'Номер телефона должен содержать десять цифр');
					break;

				case 'numeric_code':
					$aRules[] = array($sFieldName, 'checkValidClientNumericCode', 'message' => 'Цифровой код должен состоять не менее, чем из ' . SiteParams::C_NUMERIC_CODE_MIN_LENGTH . ' цифр');
					break;

				case 'email':
					$aRules[] = array($sFieldName, 'email', 'message' => 'Введите email в правильном формате');
					break;

				case 'job_income_add':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите значение поля их списка', 'range' => array_keys(Dictionaries::$aOverMoney));
					break;

				case 'have_car':
				case 'have_estate':
				case 'have_credit':
				case 'have_past_credit':
				case 'have_dependents':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите значение из списка', 'range' => array_keys(Dictionaries::$aYesNo));
					break;

				case 'job_time':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите корректный стаж работы', 'range' => array_keys(Dictionaries::$aJobTimes));
					break;

				case 'job_salary_date':
				case 'job_prepay_date':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Укажите корректное значение дней начисления', 'range' => array_keys(Dictionaries::$aMoneyDays));
					break;

				case 'secret_question':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите значение поля их списка', 'range' => array_keys(Dictionaries::$aSecretQuestions));
					break;

				case 'citizenship':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Выберите гражданство из списка.', 'range' => array_keys(Dictionaries::$aCitizenship));
					break;

				case 'inn':
					$aRules[] = array($sFieldName, 'match', 'message' => 'ИНН должно состоять из двенадцати цифр .', 'pattern' => '/^\d{' . SiteParams::C_INN_LENGTH . '}$/');
					break;

				case 'job_monthly_income':
				case 'job_monthly_outcome':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Укажите корректное значение для ежемесячного дохода.', 'range' => array_keys(Dictionaries::$aMonthlyMoney));
					break;

				case 'liabilities':
					$aRules[] = array($sFieldName, 'in', 'message' => 'Укажите корректное значение для ежемесячного дополнительного расхода.', 'range' => array_keys(Dictionaries::$aLiabilities));
					break;

				default:
					$aRules[] = array($sFieldName, 'safe');

			}

		}

		return $aRules;

	}

	/**
	 * передать в форму содержимое оригинального post-запроса
	 * для предварительной подготовки формы к валидации
	 *
	 * @param $aPostParams
	 */
	public function setOriginalPost($aPostParams)
	{

		if (!$aPostParams) {
			return;
		}

		$this->address_reg_as_res = (empty($aPostParams['address_reg_as_res']))
			? 0
			: 1;

	}

	/**
	 * проверка имени
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientName($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidClientName($attribute, $param);
	}

	/**
	 * проверка учреждения, выдавшего паспорт
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidPassportIssued($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidPassportIssued($attribute, $param);
	}

	/**
	 * проверка фио
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidFio($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidFio($attribute, $param);
	}

	/**
	 * проверка составляющих адреса
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidAddressRegion($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidAddressRegion($attribute, $param);
	}


	/**
	 * проверка, что возраст в заданном диапазоне
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidAge($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidAge($attribute, $param);
	}

	/**
	 * проверка даты выдачи паспорта на валидность
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidPassportDate($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidPassportDate($attribute, $param);
	}

	/**
	 * проверка номера второго документа на валидность
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidDocumentNumber($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidDocumentNumber($attribute, $param);
	}

	/**
	 * адрес регистрации совпадает с адресом проживания?
	 * @return bool
	 */
	private function isAddressRegAsRes()
	{
		return (bool)$this->address_reg_as_res;
	}

	/**
	 * проверка номера телефона
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientPhone($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidClientPhone($attribute, $param);
	}

	/**
	 * проверка цифрового кода
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidClientNumericCode($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidClientNumericCode($attribute, $param);
	}

	/**
	 * подключаем общий помощник по валидации разных данных
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'FormFieldValidateBehavior' => array(
				'class' => 'application.extensions.behaviors.FormFieldValidateBehavior',
			),
		);
	}

	/**
	 * @param null $aAttributes
	 *
	 * @return array
	 */

	public function getAttributes($aAttributes = null)
	{
		if (!$aAttributes) {
			$aAttributes = array();
			$aRules = $this->rules();
			foreach ($aRules as $aRule) {
				if (gettype($aRule[0]) === "string") {
					$aAttributes[] = $aRule[0];
				} elseif (gettype($aRule[0]) === "array") {
					foreach ($aRule[0] as $aSubRule) {
						if (gettype($aSubRule[0]) === "string") {
							$aAttributes[] = $aSubRule[0];
						}
					}
				}
			}
			$aAttributes = array_unique($aAttributes);
		}

		return parent::getAttributes($aAttributes);
	}

	public function getValidAttributes()
	{
		$this->validate();


		$aNoValidFields = array_keys($this->getErrors());

		$aFormFields = array_keys($this->getAttributes());

		$aDiff = array_diff($aFormFields, $aNoValidFields);

		//проверка на случай, что разницы в массивах нет (т.е. не валидны все поля)
		if ($aDiff) {
			$aValidFormData = $this->getAttributes($aDiff);
		} else {
			$aValidFormData = null;
		}

		return $aValidFormData;
	}

	/**
	 * названия атрибутов
	 * @return array
	 */
	public function attributeLabels()
	{

		return array(
			'comment'                => '',

			'entry_point_id'         => 'Источник анкеты',

			'phone'                  => 'Мобильный телефон',
			'phone_home'             => 'Домашний телефон',

			'first_name'             => 'Имя',
			'last_name'              => 'Фамилия',
			'third_name'             => 'Отчество',

			'prev_last_name'         => 'Прежняя фамилия',

			'sex'                    => 'Пол',
			'birthday'               => 'День рождения',
			'numeric_code'           => 'Цифровой код (для подтверждения повторных займов)',
			'email'                  => 'Email',
			'marital_status'         => 'Семейное положение',

			'document'               => 'Тип документа',
			'document_number'        => 'Номер документа',

			'address_reg_region'     => 'Регион (республика/край/область)',
			'address_reg_post_index' => 'Индекс',
			'address_reg_city'       => 'Населенный пункт (город, поселок, деревня)',
			'address_reg_address'    => 'Адрес (улица, дом, корпус/строение, квартира',

			'address_reg_as_res'     => 'Совпадает с адресом проживания',

			'address_res_region'     => 'Регион (республика/край/область)',
			'address_res_post_index' => 'Индекс',
			'address_res_city'       => 'Населенный пункт (город, поселок, деревня и т.д.)',
			'address_res_address'    => 'Адрес (улица, дом, корпус/строение, квартира',

			'passport_series'        => 'Серия',
			'passport_number'        => 'Номер',
			'passport_issued'        => 'Кем выдан',
			'passport_date'          => 'Дата выдачи',
			'passport_code'          => 'Код подразделения',

			'job_phone'              => 'Рабочий телефон',
			'job_less'               => 'Безработный',
			'job_company'            => 'Место работы',
			'job_position'           => 'Должность',
			'job_time'               => 'Стаж работы',
			'job_salary_date'        => 'Дни выдачи зарплаты',
			'job_prepay_date'        => 'Дни выдачи аванса',
			'job_income_add'         => 'Дополнительный доход',

			'job_contact_name'       => 'ФИО руководителя/директора',
			'job_contact_phone'      => 'Телефон руководителя/директора',

			'have_past_credit'       => 'Были кредиты в прошлом?',
			'have_dependents'        => 'Есть иждивенцы',

			'relatives_degree'       => 'Степень родства',
			'relatives_fio'          => 'ФИО',
			'relatives_phone'        => 'Телефон',

			'relatives_one_fio'      => 'ФИО знакомого/родственника',
			'relatives_one_phone'    => 'Телефон знакомого/родственника',

			'friends_fio'            => 'ФИО',
			'friends_phone'          => 'Телефон',


			// старые поля

			'job_monthly_income'     => 'Средний месячный доход',
			'job_monthly_outcome'    => 'Средний месячный расход',

			'prev_first_name'        => 'Имя',
			'prev_third_name'        => 'Отчество',

			'education'              => 'Образование',
			'inn'                    => 'ИНН',
			'citizenship'            => 'Гражданство',

			'job_director_name'      => 'ФИО директора',
			'job_director_phone'     => 'Телефон директора',

			'liabilities'            => 'Есть другие финансовые обязательства',

			'birthplace_country'     => 'Страна рождения',
			'birthplace_city'        => 'Город рождения',

			'have_car'               => 'Есть автомобиль',
			'have_estate'            => 'Есть недвижимость',
			'have_credit'            => 'Есть кредит',

			'address_reg_street'     => 'Улица',
			'address_reg_house'      => 'Дом',
			'address_reg_build'      => 'Корпус',
			'address_reg_apart'      => 'Квартира',

			'address_res_street'     => 'Улица',
			'address_res_house'      => 'Дом',
			'address_res_build'      => 'Корпус',
			'address_res_apart'      => 'Квартира',

		);
	}

	/**
	 * @return bool|void
	 */

	protected function beforeValidate()
	{
		$oPurifier = new CHtmlPurifier;
		$oPurifier->options = array(
			//'HTML.SafeObject'=>true,
			'HTML.Allowed' => '',
		);
		$aAttributes = $this->getAttributes();
		foreach ($aAttributes as &$sAttribute) {
			$sAttribute = $oPurifier->purify($sAttribute);
		}
		$this->setAttributes($aAttributes);

		return parent::beforeValidate();
	}
}
