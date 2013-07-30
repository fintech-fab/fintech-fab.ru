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
 * @property string $passport_issued; // кем выдан
 * @property string $passport_code
 * @property string $passport_date
 * @property string $document
 * @property string $document_number
 * @property string $address_reg_region
 * @property string $address_reg_city
 * @property string $address_reg_address
 *
 * + public $relatives_one_fio; // обязательный один контакт - фио знакомого или родственника @new
 * + public $relatives_one_phone; // обязательный один телефон - к фио знакомого/родственника @new
 *
 * + public $job_company; // компания
 * + public $job_position; // должность
 * + public $job_time; // стаж работы
 * + $job_monthly_income; //месячный доход
 * + $job_monthly_outcome; //месячный расход
 * + public $have_past_credit = 0; // раньше были кредиты @new
 *
 * + public $numeric_code; // цифровой код
 *
 * @property string $options
 * @property string $dt_add
 * @property string $dt_update
 * @var $model ClientForm1
 */
/*
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

class ClientData_backup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientData the static model class
	 */
	public static function model($className=__CLASS__)
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
			array('birthday, dt_add, dt_update', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.*/
			array('client_id, phone, job_phone, telecoms_operator, first_name, last_name, third_name, sex, birthday, email, description, passport_series, passport_number, passport_code, passport_date, document, document_number, address_reg_region, address_reg_city, address_reg_address, options, complete, dt_add, dt_update', 'safe'),


         );
	}

    public function checkClientByPhone($phone)
    {
        if(($client=$this->find('phone=:phone',array(':phone'=>$phone)))&&($client->complete==1))
        {
            return true;
        }
        return false;

    }

	public function clearClient(CActiveRecord &$client)
	{
		if($client)
		{
			$clientData=$client->getAttributes();
			$client_id=$client->client_id;
			foreach($clientData as &$c)
			{
				$c='';
			}
			unset($c);
			$client->setAttributes($clientData);
			$client->client_id=$client_id;
		}
		return;
	}

    public function addClient($model)
    {
		if($client=$this->find('phone=:phone',array(':phone'=>$model->phone)))
		{
			//$this->clearClient($client); //бессмысленно, т.к. при открытии пустой формы сразу проходит валидация, "зачищающая" данные в таблицы через ajax-запись
			$client->phone=$model->phone;
			$client->dt_add=date('Y-m-d H:i:s', time());
			$client->save();
			return $client;
		}
		else
		{
			$this->phone=$model->phone;
			$this->dt_add=date('Y-m-d H:i:s', time());
			$this->save();
			return $this;
		}
    }

    public function getClientIdByPhone($phone)
    {
        if($client=$this->find('phone=:phone',array(':phone'=>$phone)))
        {
            return $client->client_id;
        }
        return false;
    }


	public function getClientDataById($client_id)
	{
		if($client=$this->find('client_id=:client_id',array(':client_id'=>$client_id)))
		{
			return $client->getAttributes();
		}
		return false;
	}

	public function saveClientDataById($clientData,$client_id)
	{
		if($client=$this->find('client_id=:client_id',array(':client_id'=>$client_id)))
		{
			$client->setAttributes($clientData);
			$client->save();
			return true;
		} return false;
	}

    public function beforeSave()
    {
        $this->dt_update=date('Y-m-d H:i:s', time());
        return parent::beforeSave();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
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
			'passport_code' => 'Passport Code',
			'passport_date' => 'Passport Date',
			'document' => 'Document',
			'document_number' => 'Document Number',
			'address_reg_region' => 'Address Res Region',
			'address_reg_city' => 'Address Res City',
			'address_reg_address' => 'Address Res Address',
			'options' => 'Options',
			'dt_add' => 'Dt Add',
			'dt_update' => 'Dt Update',
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

		$criteria=new CDbCriteria;

		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('job_phone',$this->job_phone,true);
		$criteria->compare('telecoms_operator',$this->telecoms_operator);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('third_name',$this->third_name,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('passport_series',$this->passport_series,true);
		$criteria->compare('passport_number',$this->passport_number,true);
		$criteria->compare('passport_code',$this->passport_code,true);
		$criteria->compare('passport_date',$this->passport_date,true);
		$criteria->compare('document',$this->document,true);
		$criteria->compare('document_number',$this->document_number,true);
		$criteria->compare('address_reg_region',$this->address_reg_region,true);
		$criteria->compare('address_reg_city',$this->address_reg_city,true);
		$criteria->compare('address_reg_address',$this->address_reg_address,true);
		$criteria->compare('options',$this->options,true);
		$criteria->compare('dt_add',$this->dt_add,true);
		$criteria->compare('dt_update',$this->dt_update,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}