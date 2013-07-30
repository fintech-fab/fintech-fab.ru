<?php
/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectProductForm extends ClientCreateFormAbstract
{
	public $product;

	public function rules()
	{

		$aProducts=array("1"=>"3000 рублей на неделю","2"=>"6000 рублей на неделю","3"=>"10000 рублей на 2 недели");
		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'product',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(
				'product',
			),
			$aRequired
		);
		array('product', 'in', 'range' => array_keys($aProducts),'message' => 'Выберите сумму займа');

		return $aRules;

	}

	public function attributeLabels()
	{
		$labels = $this->attributeLabels();
		$labels[] = array(
			'product' => 'Сумма займа',
		);
		return $labels;
	}
}