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
	/**
	 * @var ФИО родственника/друга
	 */
	public $friends_fio;

	/**
	 * @var дополнительный телефон родственника/друга
	 */
	public $friends_phone;

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array(
				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'relatives_one_fio',
				'relatives_one_phone',
		);

		$aRules = $this->getRulesByFields(

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
		);

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
}
