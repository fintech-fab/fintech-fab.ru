<?php
/**
 * Class ChangeNumericCodeForm
 */

class ChangeNumericCodeForm extends ClientFullForm
{

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'numeric_code',
		);
		$aMyRules =
			array();
		$aRules = array_merge($this->getRulesByFields(
			array(
				'numeric_code'
			),
			$aRequired
		), $aMyRules);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'numeric_code'=>'Цифровой код'

			)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'numeric_code',

		);
	}
}

