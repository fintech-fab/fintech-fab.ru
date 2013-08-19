<?php
/**
 *
 */
class InviteToIdentificationForm extends ClientCreateFormAbstract
{

	public $go_identification;
	public $agree;

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array(
			'go_identification',
			'agree',
		);

		$aRules = $this->getRulesByFields(

			array(
				'go_identification',
			),
			$aRequired
		);
		$aRules[] = array('go_identification', 'in', 'range'=>array(1,2), 'message' => 'Ошибка!');
		$aRules[] = array('agree', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие на обработку данных');

		return $aRules;

	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'agree'=>'Согласен с условиями и передачей данных (<a data-toggle="modal" href="#privacy">подробная информация</a>)',
		);
	}


}