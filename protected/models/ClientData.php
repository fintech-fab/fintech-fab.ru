<?php

/**
 * This is the model class for table "tbl_client".
 *
 * The followings are the available columns in table 'tbl_client':
 * @property string $client_id
 * @property string $phone
 * @property string $job_phone
 * @property integer $telecoms_operator
 * @property string $first_name
 * @property string $last_name
 * @property string $third_name
 * @property integer $sex
 * @property string $birthday
 * @property string $email
 * @property string $description
 * @property string $passport_series
 * @property string $passport_number
 * @property string $passport_issued
 * @property string $passport_code
 * @property string $passport_date
 * @property string $document
 * @property string $document_number
 * @property string $address_reg_region
 * @property string $address_reg_city
 * @property string $address_reg_address
 * @property string $relatives_one_fio
 * @property string $relatives_one_phone
 * @property string $friends_fio
 * @property string $friends_one_phone
 * @property string $job_company
 * @property string $job_position
 * @property string $job_time
 * @property string $job_monthly_income
 * @property string $job_monthly_outcome
 * @property integer $have_past_credit
 * @property integer $secret_question;
 * @property string $secret_answer;
 * @property integer $numeric_code
 * @property integer $sms_code
 * @property integer $product
 * @property integer $get_way
 * @property string $options
 * @property integer $complete
 * @property int $flag_processed
 * @property string $dt_add
 * @property string $dt_update
 * @property int $flag_identified
 * @property int $flag_sms_confirmed
 * @property int $flag_archived
 *
 * @method ClientData[] findAll()
 * @method ClientData[] findAllByAttributes()
 * @method ClientData find()
 *
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

class ClientData extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientData the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			/*
			array('description, passport_code, passport_date, document, document_number, address_reg_region, address_reg_city, address_reg_address, options', 'required'),
			array('telecoms_operator, sex', 'numerical', 'integerOnly'=>true),
			array('client_id', 'length', 'max'=>11),
			array('phone', 'length', 'max'=>10),
			array('first_name, last_name, third_name, email, address_reg_address', 'length', 'max'=>255),
			array('passport_series', 'length', 'max'=>4),
			array('passport_number', 'length', 'max'=>6),
			array('passport_code', 'length', 'max'=>7),
			array('document, address_reg_region, address_reg_city', 'length', 'max'=>100),
			array('document_number', 'length', 'max'=>30),
			array('birthday, dt_add, dt_update', 'safe'),*/
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('client_id, phone, job_phone, telecoms_operator, first_name, last_name, third_name, sex, birthday, email, description, passport_series, passport_number, passport_issued, passport_code, passport_date, document, document_number, address_reg_region, address_reg_city, address_reg_address, relatives_one_fio, relatives_one_phone, friends_fio, friends_phone, job_company, job_position, job_time, job_monthly_income, job_monthly_outcome, have_past_credit, secret_question, secret_answer, numeric_code, sms_code, product, get_way, options, complete, dt_add, dt_update, flag_processed, flag_identified, flag_sms_confirmed, flag_archived', 'safe'),

		);
	}

	/**
	 * @param $sPhone
	 * @return ClientData
	 */
	public function scopePhone($sPhone)
	{
		$this->getDbCriteria()->addColumnCondition(array(
			'phone' => $sPhone,
			'flag_processed' => 0,
			'flag_archived' => 0,
		));
		return $this;
	}

	/**
	 * @param $iClientId
	 * @return ClientData
	 */

	public function scopeClientId($iClientId)
	{
		$this->getDbCriteria()->addColumnCondition(array(
			'client_id' => $iClientId
		));
		return $this;
	}


	//проверяем, если ли клиент с таким же номером телефона и заполненной анкетой
	public static function checkClientByPhone($phone)
	{
		$oClientData = self::model()->scopePhone($phone)->find();

		return (
			$oClientData &&
			$oClientData->complete == 1
		);
	}

	/**
	 * @param ClientCreateFormAbstract $oClientForm
	 * @return ClientData
	 */

	public static function addClient($sPhone)
	{
		$oClientData = self::model()->scopePhone($sPhone)->find();
		if (!$oClientData) {
			$oClientData = new self;
		}

		if($oClientData&&$oClientData->complete==1){
			$oClientData->flag_archived=1;
			$oClientData->save();
			$oClientData = new self;
		}

		$oClientData->phone = $sPhone;
		$oClientData->dt_add = date('Y-m-d H:i:s', time());
		$oClientData->flag_processed = 0;
		$oClientData->save();
		return $oClientData;
	}

	/**
	 * @param $sPhone
	 * @return bool|string
	 */
	public static function getClientIdByPhone($sPhone)
	{
		$oClientData = self::model()->scopePhone($sPhone)->find();
		return ($oClientData) ? $oClientData->client_id : null;
	}

	/**
	 * @param $client_id
	 * @return array|null
	 */

	public static function getClientDataById($client_id)
	{
		$oClientData = self::model()->scopeClientId($client_id)->find();
		return ($oClientData) ? $oClientData->getAttributes() : null;

	}

	/**
	 * @param $client_id
	 * @param string $sCode
	 * @return bool
	 */
	public static function compareSMSCodeByClientId($client_id, $sCode)
	{
		$aClientData = self::model()->getClientDataById($client_id);
		if($aClientData['sms_code']==$sCode)
		{
			return true;
		}

		return false;
	}

	/**
	 * @param $aClientFormData
	 * @param $client_id
	 * @return bool
	 */
	public static function saveClientDataById($aClientFormData, $client_id)
	{
		$oClientData = self::model()->scopeClientId($client_id)->find();
		if ($oClientData) {
			$oClientData->setAttributes($aClientFormData);
			$oClientData->save();
			return true;
		}
		return false;
	}

	public function beforeSave()
	{
		$this->dt_update = date('Y-m-d H:i:s', time());
		$sDateFormatInBase = "Y-m-d";
		$this->birthday=date($sDateFormatInBase,strtotime($this->birthday));
		$this->passport_date=date($sDateFormatInBase,strtotime($this->passport_date));
		return parent::beforeSave();
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Client',
			'phone' => 'Phone',
			'job_phone' => 'Job Phone',
			'telecoms_operator' => 'Telecoms Operator',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'third_name' => 'Third Name',
			'sex' => 'Sex',
			'birthday' => 'Birthday',
			'email' => 'Email',
			'description' => 'Description',
			'passport_series' => 'Passport Series',
			'passport_number' => 'Passport Number',
			'passport_issued' => 'Passport Issued',
			'passport_code' => 'Passport Code',
			'passport_date' => 'Passport Date',
			'document' => 'Document',
			'document_number' => 'Document Number',
			'address_reg_region' => 'Address Reg Region',
			'address_reg_city' => 'Address Reg City',
			'address_reg_address' => 'Address Reg Address',
			'relatives_one_fio' => 'Relatives One Fio',
			'relatives_one_phone' => 'Relatives One Phone',
			'friends_fio' => 'Friends Fio',
			'friends_phone' => 'Friends Phone',
			'job_company' => 'Job Company',
			'job_position' => 'Job Position',
			'job_time' => 'Job Time',
			'job_monthly_income' => 'Job Monthly Income',
			'job_monthly_outcome' => 'Job Monthly Outcome',
			'have_past_credit' => 'Have Past Credit',
			'secret_question' => 'Secret Question',
			'secret_answer' => 'Secret  Answer',
			'numeric_code' => 'Numeric Code',
			'sms_code' => 'SMS Code',
			'product' => 'Product',
			'get_way' => 'Get Way',
			'options' => 'Options',
			'complete' => 'Complete',
			'dt_add' => 'Dt Add',
			'dt_update' => 'Dt Update',
			'flag_identified' => 'Flag Identified',
			'flag_sms_confirmed' => 'Flag SMS Confirmed',
			'flag_processed' => 'Flag Processed',
			'flag_archived' => 'Flag Archived',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('client_id', $this->client_id, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('job_phone', $this->job_phone, true);
		$criteria->compare('telecoms_operator', $this->telecoms_operator);
		$criteria->compare('first_name', $this->first_name, true);
		$criteria->compare('last_name', $this->last_name, true);
		$criteria->compare('third_name', $this->third_name, true);
		$criteria->compare('sex', $this->sex);
		$criteria->compare('birthday', $this->birthday, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('passport_series', $this->passport_series, true);
		$criteria->compare('passport_number', $this->passport_number, true);
		$criteria->compare('passport_issued', $this->passport_issued, true);
		$criteria->compare('passport_code', $this->passport_code, true);
		$criteria->compare('passport_date', $this->passport_date, true);
		$criteria->compare('document', $this->document, true);
		$criteria->compare('document_number', $this->document_number, true);
		$criteria->compare('address_reg_region', $this->address_reg_region, true);
		$criteria->compare('address_reg_city', $this->address_reg_city, true);
		$criteria->compare('address_reg_address', $this->address_reg_address, true);
		$criteria->compare('relatives_one_fio', $this->relatives_one_fio, true);
		$criteria->compare('relatives_one_phone', $this->relatives_one_phone, true);
		$criteria->compare('friends_fio', $this->friends_fio, true);
		$criteria->compare('friends_phone', $this->friends_phone, true);
		$criteria->compare('job_company', $this->job_company, true);
		$criteria->compare('job_position', $this->job_position, true);
		$criteria->compare('job_time', $this->job_time, true);
		$criteria->compare('job_monthly_income', $this->job_monthly_income, true);
		$criteria->compare('job_monthly_outcome', $this->job_monthly_outcome, true);
		$criteria->compare('have_past_credit', $this->have_past_credit);
		$criteria->compare('secret_question', $this->secret_question);
		$criteria->compare('secret_answer', $this->secret_answer);
		$criteria->compare('numeric_code', $this->numeric_code);
		$criteria->compare('sms_code', $this->sms_code);
		$criteria->compare('product', $this->product);
		$criteria->compare('get_way', $this->get_way);
		$criteria->compare('options', $this->options, true);
		$criteria->compare('complete', $this->complete);
		$criteria->compare('dt_add', $this->dt_add, true);
		$criteria->compare('dt_update', $this->dt_update, true);
		$criteria->compare('flag_identified', $this->flag_identified, true);
		$criteria->compare('flag_sms_confirmed', $this->flag_sms_confirmed, true);
		$criteria->compare('flag_processed', $this->flag_processed, true);
		$criteria->compare('flag_archived', $this->flag_archived, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
} 
