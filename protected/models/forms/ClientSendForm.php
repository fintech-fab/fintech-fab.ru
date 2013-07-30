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
		$aRequired = array_merge(
			array(
				'numeric_code',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(
				'numeric_code',
			),
			$aRequired
		);
		$aRules[] = array('complete', 'required', 'requiredValue' => 1,'message'=>'Необходимо подтвердить свое согласие на обработку данных');

		return $aRules;

	}

	public function attributeLabels()
	{
		$labels = $this->attributeLabels();
		$labels[] = array(
			'complete' => 'Я подтверждаю верность введенных данных и даю разрешение на их обработку и хранение',
		);
		return $labels;
	}

}