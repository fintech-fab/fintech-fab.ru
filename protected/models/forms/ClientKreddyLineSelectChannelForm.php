<?php

/**
 * Class ClientKreddyLineSelectChannelForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientKreddyLineSelectChannelForm extends ClientCreateFormAbstract
{
	public $channel_id;

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'channel_id',
		);

		$aRules = $this->getRulesByFields(

			array(
				'channel_id',
			),
			$aRequired
		);
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выберите правильный способ получения');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return
			array(
				'channel_id' => 'Способ получения'
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
