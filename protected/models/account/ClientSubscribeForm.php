<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientSubscribeForm extends ClientCreateFormAbstract
{
	public $product;
	public $channel_type;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('product', 'required', 'message' => 'Для оформления подписки требуется выбрать продукт'),
			array('product', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getClientProductsAndChannelsList()), 'message' => 'Для оформления подписки требуется выбрать продукт'),
			//TODO сделать запрос способа получения из API
			//array('product', 'channel_type', 'range' => array_keys(Yii::app()->adminKreddyApi->getProductsList()), 'message' => 'Выберите способ получения')
			array('channel_type', 'safe')
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
			array('product' => 'Выберите продукт'),
			array('channel_type' => 'Выберите способ получения продукта')
		);
	}
}
