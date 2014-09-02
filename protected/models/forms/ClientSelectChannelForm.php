<?php
/**
 * Class ClientSelectChannelForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectChannelForm extends ClientCreateFormAbstract
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
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Dictionaries::aChannels(Yii::app()->clientForm->getSessionProduct())), 'message' => 'Выбери правильный способ');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('channel_id' => 'Способ получения',)
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
