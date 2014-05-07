<?php

/**
 * Class ClientSelectProductForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientKreddyLineSelectProductForm extends ClientCreateFormAbstract
{
	public $product;
	public $fast_reg;

	/**
	 * @return array
	 */
	public function rules()
	{

		// всегда обязательные поля
		$aRules[] = array('product', 'required');
		$aRules[] = array('product', 'in', 'range' => array_keys(ProductsChannelsComponent::getKreddyLineProductsCosts()), 'message' => 'Выберите сумму займа');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('product' => 'Выберите Пакет займов');
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'product',
		);
	}
}
