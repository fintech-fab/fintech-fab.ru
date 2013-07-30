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

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'relatives_one_fio',
				'relatives_one_phone',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(

				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'relatives_one_fio',
				'relatives_one_phone',
			),
			$aRequired
		);

		return $aRules;

	}

}