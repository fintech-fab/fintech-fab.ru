<?php
/**
 * Class ClientFlexibleProductForm
 *
 */
class ClientFlexibleProductForm extends ClientCreateFormAbstract
{
	public $amount;
	public $time;
	public $channel_id;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('amount, time, channel_id', 'required'),
			array('amount', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getFlexibleProduct()), 'message' => 'Выберите сумму займа'),
			array('time', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getFlexibleProductTime()), 'message' => 'Выберите период займа'),
			array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выберите правильный способ получения займа')
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
			array('amount' => 'Выберите сумму займа',),
			array('time' => 'Выберите период',),
			array('channel_id' => 'ID канала получения',)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'amount',
			'time',
			'channel_id'
		);
	}
}
