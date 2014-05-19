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
		$aRules[] = array('channel_id', 'required', 'message' => 'Куда перевести деньги? На карту или мобильный?');

		$aRulesFields = array(
			'channel_id',
		);

		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannelsKreddyLine()), 'message' => 'Выберите правильный способ получения');

		$aRules = CMap::mergeArray(
			$this->getRulesByFields($aRulesFields),
			$aRules);

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
