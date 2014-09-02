<?php

/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectProductForm extends ClientCreateFormAbstract
{
	public $product;
	public $channel_id;

	/**
	 * @return array
	 */
	public function rules()
	{

		// всегда обязательные поля
		$aRules[] = array('product, channel_id', 'required');
		$aRules[] = array('product', 'in', 'range' => array_keys(Yii::app()->productsChannels->getProducts()), 'message' => 'Выбери сумму перевода');
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выбери правильный способ получения денег');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Выбери продукт'),
			array('channel_id' => 'Выбери способ получения денег')
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'product',
			'channel_id'
		);
	}
}
