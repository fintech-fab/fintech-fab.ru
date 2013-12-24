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
		);
	}
}
