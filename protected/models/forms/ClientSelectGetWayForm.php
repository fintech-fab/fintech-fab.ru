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

		$aWays=array("1"=>"На карту Kreddy MasterCard","2"=>"На сотовый телефон");
		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'get_way',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(
				'get_way',
			),
			$aRequired
		);
		array('get_way', 'in', 'range' => array_keys($aWays),'message' => 'Выберите сумму займа');

		return $aRules;

	}

	public function attributeLabels()
	{
		$labels = parent::attributeLabels();
		$labels = array_merge($labels,
			array(
				'get_way' => 'Способ получения',
			));
		return $labels;
	}
}