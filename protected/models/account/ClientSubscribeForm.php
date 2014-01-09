<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientSubscribeForm extends ClientCreateFormAbstract
{
	public $product;
	public $channel; //TODO channel

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('product', 'required', 'message' => 'Для оформления Пакета займов требуется выбрать продукт'),
			//TODO channel
			array('product', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getClientProductsAndChannelsList()), 'message' => 'Для оформления Пакета займов требуется выбрать продукт'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Выберите продукт')
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'product'
		);
	}
}
