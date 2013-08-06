<?php
/**
 *
 */
class InviteToIdentificationForm extends ClientCreateFormAbstract
{

	public $go;

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'go',
			)
		);

		$aRules = $this->getRulesByFields(

			array(
				'go',
			),
			$aRequired
		);

		return $aRules;

	}

}