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
		$aRules[] = array('product', 'in', 'range' => array_keys(Yii::app()->productsChannels->getProducts()), 'message' => 'Выберите сумму займа');
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выберите правильный способ получения займа');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Выберите Пакет займов'),
			array('channel_id' => 'Выберите способ получения займа')
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
