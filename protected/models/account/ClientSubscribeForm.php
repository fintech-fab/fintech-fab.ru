<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientSubscribeForm extends ClientCreateFormAbstract
{
	public $product;
	public $channel_id;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('product', 'required', 'message' => 'Для подключения Пакета займов требуется выбрать продукт'),
			array('channel_id', 'required', 'message' => 'Для подключения Пакета займов требуется выбрать канал', 'on' => 'channelRequired'),
			array('product', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getClientProductsList()), 'message' => 'Для подключения Пакета займов требуется выбрать продукт'),
			array('channel_id', 'in', 'range' => array_values(Yii::app()->adminKreddyApi->getSelectedProductChannelsList()), 'message' => 'Для подключения Пакета займов требуется выбрать канал', 'on' => 'channelRequired'),
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
			array(
				'channel_id' => 'Выберите продукт',
				'product' => 'Выберите канал',
			)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'product',
			'channel_id',
		);
	}
}
