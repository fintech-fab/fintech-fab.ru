<?php
/**
 * Class ClientSendForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSendForm extends ClientCreateFormAbstract
{
	public $complete;

	public function rules()
	{

		// всегда обязательные поля
		$aRules = $this->getRulesByFields(

			array(
				'numeric_code',
			)
		);
		$aRules[] = array('complete', 'required', 'requiredValue' => 1,'message'=>'Необходимо подтвердить свое согласие на обработку данных');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('complete' => 'Я подтверждаю верность введенных данных и даю разрешение на их обработку и хранение',)
		);
	}

}
