<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientSubscribeForm extends ClientCreateFormAbstract
{
	public $product;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('product', 'required', 'message' => 'Для оформления Пакета займов требуется выбрать продукт'),
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
			array('product' => 'Выберите продукт'),
			array('channel_id' => 'Выберите способ получения продукта')
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
