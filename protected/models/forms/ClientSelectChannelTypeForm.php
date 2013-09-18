<?php
/**
 * Class ClientSelectChannelTypeForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectChannelTypeForm extends ClientCreateFormAbstract
{
	public $channel_type;

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'channel_type',
		);

		$aRules = $this->getRulesByFields(

			array(
				'channel_type',
			),
			$aRequired
		);
		$aRules[] = array('channel_type', 'in', 'range' => array_keys(Dictionaries::aChannels(Yii::app()->clientForm->getSessionProduct())), 'message' => 'Выберите правильный способ');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('channel_type' => 'Способ получения',)
		);
	}
}
