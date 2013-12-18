<?php
/**
 * Class ClientAddressDataForm
 */
class ClientAddressDataForm extends ClientFullForm
{
	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'address_reg_region',
			'address_reg_city',
			'address_reg_address',

			'relatives_one_fio',
			'relatives_one_phone',

			'phone',
		);

		$aRules = $this->getRulesByFields(
			array(
				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'address_reg_as_res',

				'address_res_region',
				'address_res_city',
				'address_res_address',

				'relatives_one_fio',
				'relatives_one_phone',

				'friends_fio',
				'friends_phone',
			),
			$aRequired
		);

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'address_reg_region',
			'address_reg_city',
			'address_reg_address',

			'address_reg_as_res',

			'address_res_region',
			'address_res_city',
			'address_res_address',

			'relatives_one_fio',
			'relatives_one_phone',

			'friends_fio',
			'friends_phone',

			//обязательно требуется для валидации, берется из информации предыдущих форм
			'phone',
		);
	}
}
