<?php
/**
 * Class ClientSelectGetWayForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectGetWayForm extends ClientCreateFormAbstract
{
	public $get_way;

	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'get_way',
			)
		);

		$aRules = $this->getRulesByFields(

			array(
				'get_way',
			),
			$aRequired
		);
		array('get_way', 'in', 'range' => array_keys(Dictionaries::$aWays),'message' => 'Выберите сумму займа');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('get_way' => 'Способ получения',)
		);
	}
}
