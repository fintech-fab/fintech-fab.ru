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
		$aRules[] = array('product', 'required', 'message' => 'Какой КРЕДДИтный лимит интересует?');
		$aRules[] = array('product', 'in', 'range' => array_keys(ProductsChannelsComponent::getKreddyLineProductsCosts()), 'message' => 'Какой КРЕДДИтный лимит интересует?');

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array('product' => 'Какой КРЕДДИтный лимит интересует?');
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
