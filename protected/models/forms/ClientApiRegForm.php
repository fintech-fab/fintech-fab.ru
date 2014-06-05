<?php

/**
 * Class ClientApiRegForm
 */
class ClientApiRegForm extends ClientFastRegForm
{
	public $pay_type;

	/**
	 * @return array
	 */
	public function rules()
	{

		$aRequired = array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'agree',
			'phone',
			'product',
			'channel_id',
			'pay_type',
			'birthday'
		);

		$aRulesFields = array(
			'product',
			'channel_id',
			'first_name',
			'last_name',
			'third_name',
			'email',
			'phone',
			'birthday',
		);

		$aRules = $this->getRulesByFields(
			$aRulesFields,
			$aRequired
		);

		$aRules[] = array('product', 'in', 'range' => array_keys(Yii::app()->productsChannels->getProducts()), 'message' => 'Выберите сумму займа');
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выберите правильный способ получения займа');
		$aRules[] = array('pay_type', 'in', 'range' => array(3, 4), 'message' => 'Выберите правильный способ оплаты подключения');

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'agree',
			'phone',
			'product',
			'channel_id',
			'pay_type',
			'birthday',
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('pay_type' => 'Выберите способ оплаты подключения')
		);
	}

}
