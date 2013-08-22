<?php
/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectProductForm2 extends ClientCreateFormAbstract
{
	public $product;

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array(
			'product',
		);


		$aRules = $this->getRulesByFields(

			array(
				'product',
			),
			$aRequired
		);
		$aRules[] = array('product', 'in', 'range' => array_keys(Dictionaries::$aProducts2), 'message' => 'Выберите сумму займа');


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
