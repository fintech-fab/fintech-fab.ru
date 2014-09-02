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
			array('channel_id', 'required', 'message' => 'Для оформления запроса на перевод денег требуется выбрать способ получения'),
			array('channel_id', 'in', 'range' => array_values(Yii::app()->adminKreddyApi->getClientSubscriptionChannels()), 'message' => 'Для оформления запроса на перевод денег требуется выбрать способ получения'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			array('channel_id' => 'Выбери способ получения денег')
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
