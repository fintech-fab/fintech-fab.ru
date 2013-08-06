<?php
/**
 *
 */
class InviteToIdentificationForm extends ClientCreateFormAbstract
{

	public $go_identification;

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array(
			'go_identification',
		);

		$aRules = $this->getRulesByFields(

			array(
				'go_identification',
			),
			$aRequired
		);
		$aRules[] = array('go_identification', 'required', 'requiredValue' => 1, 'message' => 'Ошибка!');

		return $aRules;

	}

}