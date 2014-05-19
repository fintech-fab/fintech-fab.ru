<?php

/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientKreddyLineSelectPayTypeForm extends ClientCreateFormAbstract
{
	public $pay_type;

	/**
	 * @return array
	 */
	public function rules()
	{

		// всегда обязательные поля
		$aRules[] = array('pay_type', 'required', 'message' => 'Когда планируешь оплатить сервис? Сразу или позже?');
		$aRules[] = array('pay_type', 'in', 'range' => array_keys(Dictionaries::$aPayTypes), 'message' => 'Когда планируешь оплатить сервис? Сразу или позже?');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('pay_type' => 'Выберите метод оплаты');

	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'pay_type',
		);
	}
}
