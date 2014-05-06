<?php

/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientKreddyLineSelectPayTypeForm extends ClientCreateFormAbstract
{
	public $type;

	/**
	 * @return array
	 */
	public function rules()
	{

		// всегда обязательные поля
		$aRules[] = array('type', 'required');
		$aRules[] = array('type', 'in', 'range' => array(3, 4), 'message' => 'Выберите метод оплаты');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('type' => 'Выберите метод оплаты');

	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'type',
		);
	}
}
