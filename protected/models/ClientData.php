<?php
/**
 * This is the model class for table "tbl_client".
 *
 * The followings are the available columns in table 'tbl_client':
 *
 * @property string  $client_id
 * @property string  $ip
 * @property string  $entry_point
 * @property string  $tracking_id
 * @property string  $phone
 * @property string  $password
 * @property string  $job_phone
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $third_name
 * @property integer $sex
 * @property string  $birthday
 * @property string  $birthplace
 * @property string  $email
 * @property string  $passport_series
 * @property string  $passport_number
 * @property string  $passport_issued
 * @property string  $passport_code
 * @property string  $passport_date
 * @property string  $document
 * @property string  $document_number
 * @property integer $address_reg_region
 * @property string  $address_reg_city
 * @property string  $address_reg_address
 * @property integer $address_res_region
 * @property string  $address_res_city
 * @property string  $address_res_address
 * @property integer $address_reg_as_res
 * @property string  $relatives_one_fio
 * @property string  $relatives_one_phone
 * @property string  $friends_fio
 * @property string  $friends_phone
 * @property string  $job_company
 * @property string  $job_position
 * @property string  $job_time
 * @property string  $job_monthly_income
 * @property string  $job_monthly_outcome
 * @property integer $have_past_credit
 * @property integer $secret_question;
 * @property string  $secret_answer  ;
 * @property integer $numeric_code
 * @property integer $sms_code
 * @property integer $product
 * @property string  $channel_id
 * @property string  $flex_amount
 * @property string  $flex_time
 * @property integer $complete
 * @property string  $dt_add
 * @property string  $dt_update
 * @property int     $flag_sms_confirmed
 * @property int     $flag_archived
 * @method ClientData[] findAll()
 * @method ClientData[] findAllByAttributes()
 * @method ClientData find()
 *
 */

class ClientData extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
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
			array('passport_code, passport_date, document, document_number, address_reg_region, address_reg_city, address_reg_address', 'required'),
			array('sex', 'numerical', 'integerOnly'=>true),
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
			array('client_id, ip, entry_point, tracking_id, phone, password, job_phone, first_name, last_name, third_name, sex, birthday, email, passport_series, passport_number, passport_issued, passport_code, passport_date, document, document_number, address_reg_region, address_reg_city, address_reg_address, address_res_region, address_res_city, address_res_address, address_reg_as_res, relatives_one_fio, relatives_one_phone, friends_fio, friends_phone, job_company, job_position, job_time, job_monthly_income, job_monthly_outcome, have_past_credit, secret_question, secret_answer, numeric_code, sms_code, product, channel_id, flex_amount, flex_time, complete, dt_add, dt_update, flag_sms_confirmed, flag_archived, income_source, educational_institution_name, educational_institution_phone, status, loan_purpose, birthplace', 'safe'),
		);
	}

	/**
	 * @param $sPhone
	 *
	 * @return ClientData
	 */
	public function scopePhone($sPhone)
	{
		$this->getDbCriteria()->addColumnCondition(array(
			'phone'              => $sPhone,
			'flag_sms_confirmed' => 0,
			'flag_archived'      => 0,
		));

		return $this;
	}

	/**
	 * @param $iClientId
	 *
	 * @return ClientData
	 */

	public function scopeClientId($iClientId)
	{
		$this->getDbCriteria()->addColumnCondition(array(
			'client_id' => $iClientId
		));

		return $this;
	}

	/**
	 * Проверяем, если ли клиент с таким же номером телефона и заполненной анкетой
	 * @param $phone
	 *
	 * @return bool
	 */

	public static function checkClientByPhone($phone)
	{
		$oClientData = self::model()->scopePhone($phone)->find();

		return (
			$oClientData &&
			$oClientData->complete == 1
		);
	}

	/**
	 * @param $sPhone
	 *
	 * @return ClientData
	 */

	public static function addClient($sPhone)
	{
		$oClientData = self::model()->scopePhone($sPhone)->find();
		if (!$oClientData) {
			$oClientData = new self;
		}

		if ($oClientData && $oClientData->complete == 1) {
			$oClientData->flag_archived = 1;
			$oClientData->save();
			$oClientData = new self;
		}

		$oClientData->phone = $sPhone;
		$oClientData->dt_add = date('Y-m-d H:i:s', time());
		$oClientData->save();

		return $oClientData;
	}

	/**
	 * @param $sPhone
	 *
	 * @return bool|string
	 */
	public static function getClientIdByPhone($sPhone)
	{
		$oClientData = self::model()->scopePhone($sPhone)->find();

		return ($oClientData) ? $oClientData->client_id : null;
	}

	/**
	 * @param $client_id
	 *
	 * @return array|null
	 */

	public static function getClientDataById($client_id)
	{
		$oClientData = self::model()->scopeClientId($client_id)->find();

		return ($oClientData) ? $oClientData->getAttributes() : null;

	}

	/**
	 * @param string  $sCode
	 * @param integer $client_id
	 *
	 * @return bool
	 */
	public static function compareSMSCodeByClientId($sCode, $client_id)
	{
		$aClientData = self::model()->getClientDataById($client_id);

		return ($aClientData['sms_code'] == $sCode);
	}

	/**
	 * @param $aClientFormData
	 * @param $client_id
	 *
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

	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
		$this->dt_update = date('Y-m-d H:i:s', time());
		$sDateFormatInBase = "Y-m-d";
		$this->birthday = date($sDateFormatInBase, strtotime($this->birthday));
		$this->passport_date = date($sDateFormatInBase, strtotime($this->passport_date));

		return parent::beforeSave();
	}

	/**
	 *
	 */
	protected function afterFind()
	{
		$sDateFormatInBase = "d.m.Y";
		$this->birthday = date($sDateFormatInBase, strtotime($this->birthday));
		$this->passport_date = date($sDateFormatInBase, strtotime($this->passport_date));
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
			'client_id'           => 'Client',
			'ip'                  => 'IP',
			'entry_point'         => 'Entry point',
			'tracking_id'         => 'Tracking ID',
			'phone'               => 'Phone',
			'password'            => 'Password',
			'job_phone'           => 'Job Phone',
			'first_name'          => 'First Name',
			'last_name'           => 'Last Name',
			'third_name'          => 'Third Name',
			'sex'                 => 'Sex',
			'birthday'            => 'Birthday',
			'email'               => 'Email',
			'passport_series'     => 'Passport Series',
			'passport_number'     => 'Passport Number',
			'passport_issued'     => 'Passport Issued',
			'passport_code'       => 'Passport Code',
			'passport_date'       => 'Passport Date',
			'document'            => 'Document',
			'document_number'     => 'Document Number',
			'address_reg_region'  => 'Address Reg Region',
			'address_reg_city'    => 'Address Reg City',
			'address_reg_address' => 'Address Reg Address',
			'address_res_region'  => 'Address Res Region',
			'address_res_city'    => 'Address Res City',
			'address_res_address' => 'Address Res Address',
			'address_reg_as_res'  => 'Address Reg As Res',
			'relatives_one_fio'   => 'Relatives One Fio',
			'relatives_one_phone' => 'Relatives One Phone',
			'friends_fio'         => 'Friends Fio',
			'friends_phone'       => 'Friends Phone',
			'job_company'         => 'Job Company',
			'job_position'        => 'Job Position',
			'job_time'            => 'Job Time',
			'job_monthly_income'  => 'Job Monthly Income',
			'job_monthly_outcome' => 'Job Monthly Outcome',
			'have_past_credit'    => 'Have Past Credit',
			'secret_question'     => 'Secret Question',
			'secret_answer'       => 'Secret  Answer',
			'numeric_code'        => 'Numeric Code',
			'sms_code'            => 'SMS Code',
			'product'             => 'Product',
			'channel_id'          => 'Channel ID',
			'complete'            => 'Complete',
			'dt_add'              => 'Dt Add',
			'dt_update'           => 'Dt Update',
			'flag_sms_confirmed'  => 'Flag SMS Confirmed',
			'flag_archived'       => 'Flag Archived',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('client_id', $this->client_id, true);
		$criteria->compare('ip', $this->ip, true);
		$criteria->compare('entry_point', $this->entry_point, true);
		$criteria->compare('tracking_id', $this->tracking_id, true);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('job_phone', $this->job_phone, true);
		$criteria->compare('first_name', $this->first_name, true);
		$criteria->compare('last_name', $this->last_name, true);
		$criteria->compare('third_name', $this->third_name, true);
		$criteria->compare('sex', $this->sex);
		$criteria->compare('birthday', $this->birthday, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('passport_series', $this->passport_series, true);
		$criteria->compare('passport_number', $this->passport_number, true);
		$criteria->compare('passport_issued', $this->passport_issued, true);
		$criteria->compare('passport_code', $this->passport_code, true);
		$criteria->compare('passport_date', $this->passport_date, true);
		$criteria->compare('document', $this->document, true);
		$criteria->compare('document_number', $this->document_number, true);
		$criteria->compare('address_reg_region', $this->address_reg_region);
		$criteria->compare('address_reg_city', $this->address_reg_city, true);
		$criteria->compare('address_reg_address', $this->address_reg_address, true);
		$criteria->compare('address_res_region', $this->address_reg_region);
		$criteria->compare('address_res_city', $this->address_reg_city, true);
		$criteria->compare('address_res_address', $this->address_reg_address, true);
		$criteria->compare('address_reg_as_res', $this->address_reg_region);
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
		$criteria->compare('channel_id', $this->channel_id);
		$criteria->compare('flex_amount', $this->flex_amount);
		$criteria->compare('flex_time', $this->flex_time);
		$criteria->compare('complete', $this->complete);
		$criteria->compare('dt_add', $this->dt_add, true);
		$criteria->compare('dt_update', $this->dt_update, true);
		$criteria->compare('flag_sms_confirmed', $this->flag_sms_confirmed, true);
		$criteria->compare('flag_archived', $this->flag_archived, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
