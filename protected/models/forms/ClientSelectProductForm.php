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

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'product',
			)
		);

		$aRules = $this->getRulesByFields(

			array(
				'product',
			),
			$aRequired
		);
		array('product', 'in', 'range' => array_keys(Dictionaries::$aProducts),'message' => 'Выберите сумму займа');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Сумма займа',)
		);
	}
}
