<?php
/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSelectProductForm2 extends ClientCreateFormAbstract
{
	public $product;

	/**
	 * @return array
	 */
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
		$aRules[] = array('product', 'in', 'range' => array_keys(Yii::app()->productsChannels->getProducts()), 'message' => 'Выберите сумму займа');


		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Выберите Пакет займов',)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'product'
		);
	}
}
