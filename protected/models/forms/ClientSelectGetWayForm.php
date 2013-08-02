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
		$aRequired = array(
				'get_way',
			);

		$aRules = $this->getRulesByFields(

			array(
				'get_way',
			),
			$aRequired
		);
		$aRules[]=array('get_way', 'in', 'range' => array_keys(Dictionaries::aWays(Yii::app()->session['product'])),'message' => 'Выберите правильный способ');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			//parent::attributeLabels(),
			array('get_way' => 'Способ получения',)
		);
	}
}
