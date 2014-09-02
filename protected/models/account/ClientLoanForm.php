<?php
/**
 * Class ClientSubscribeForm
 *
 */
class ClientLoanForm extends ClientCreateFormAbstract
{
	public $channel_id;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('channel_id', 'required', 'message' => 'Для оформления займа требуется выбрать способ его получения'),
			array('channel_id', 'in', 'range' => array_values(Yii::app()->adminKreddyApi->getClientSubscriptionChannels()), 'message' => 'Для оформления займа требуется выбрать способ его получения'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			array('channel_id' => 'Выбери способ получения продукта')
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'channel_id'
		);
	}
}
