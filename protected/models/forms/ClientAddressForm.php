<?php
/**
 * Форма адресных данных
 *
 * = Поля формы =
 * Адрес:
 * + Регион
 * + Город
 * + Адрес
 * Контактное лицо:
 * - ФИО
 * - Номер телефона
 * Class ClientCreateForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientAddressForm extends ClientCreateFormAbstract
{
	public function rules()
	{
		//TODO подумать, насколько это фэншуйно
		$phone = Yii::app()->clientForm->getSessionPhone();

		// всегда обязательные поля
		$aRequired = array(
				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'relatives_one_fio',
				'relatives_one_phone',
		);

		$aRules =
			array(
				array('relatives_one_phone', 'compare', 'operator'=>'!=','compareValue'=>$phone,'message'=>'Номер не должен совпадать с вашим номером телефона!'),
				array('friends_phone', 'compare', 'operator'=>'!=','compareValue'=>$phone,'message'=>'Номер не должен совпадать с вашим номером телефона!'),

				array('friends_phone', 'compare', 'operator'=>'!=','compareAttribute'=>'relatives_one_phone','message'=>'Номер не должен совпадать с телефоном контактного лица.'),
				array('relatives_one_phone', 'compare', 'operator'=>'!=','compareAttribute'=>'friends_phone','message'=>'Номер не должен совпадать телефоном дополнительного контакта.'),
			);

		$aRules = array_merge($aRules, $this->getRulesByFields(

			array(

				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'relatives_one_fio',
				'relatives_one_phone',

				'friends_fio',
				'friends_phone',
			),
			$aRequired
		));

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('friends_fio' => 'ФИО',
				  'friends_phone'=>'Телефон',)
		);
	}

	/**
	 * @return bool|void
	 *
	 * Перед валидацией приводим номера телефонов к 10-значному виду для сравнения с phone
	 *
	 */
	public function beforeValidate()
	{
		if ($this->relatives_one_phone)
		{
			//очистка данных
			$this->relatives_one_phone = ltrim( $this->relatives_one_phone, '+ ' );
			$this->relatives_one_phone = preg_replace('/[^\d]/', '', $this->relatives_one_phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if(strlen($this->relatives_one_phone) == 11){
				$this->relatives_one_phone = substr($this->relatives_one_phone,1,10);
			}
		}

		if ($this->friends_phone)
		{
			//очистка данных
			$this->friends_phone = ltrim( $this->friends_phone, '+ ' );
			$this->friends_phone = preg_replace('/[^\d]/', '', $this->friends_phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if(strlen($this->friends_phone) == 11){
				$this->friends_phone = substr($this->friends_phone,1,10);
			}
		}

		return parent::beforeValidate();
	}
}
