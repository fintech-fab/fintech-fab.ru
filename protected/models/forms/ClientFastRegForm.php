<?php

/**
 * Class ClientFastRegForm
 */
class ClientFastRegForm extends ClientFullForm
{
	public $product;
	public $channel_id;
	public $fast_reg;

	private $_validators;

	/**
	 * @return array
	 */
	public function rules()
	{
		if ($this->fast_reg) {
			$aRequired = array(
				'first_name',
				'last_name',
				'third_name',
				'email',
				'agree',
				'phone',
			);
		} else {
			$aRequired = array(
				'product',
				'channel_id',
			);
		}

		$aRulesFields = array(
			'product',
			'channel_id',
			'first_name',
			'last_name',
			'third_name',
			'email',
			'phone',
		);

		//если быстрая регистрация, то нужно добавить также еще это правило
		if ($this->fast_reg) {
			$aRulesFields[] = 'agree';
		}

		$aRules = $this->getRulesByFields(
			$aRulesFields,
			$aRequired
		);

		$aRules[] = array('product', 'in', 'range' => array_keys(Yii::app()->productsChannels->getProducts()), 'message' => 'Выбери сумму займа');
		$aRules[] = array('channel_id', 'in', 'range' => array_keys(Yii::app()->productsChannels->getChannels()), 'message' => 'Выбери правильный способ получения займа');
		$aRules[] = array('fast_reg', 'safe');

		//если не быстрая регистрация, то нужно сделать это поле безопасным во избежание warnings
		if (!$this->fast_reg) {
			$aRules[] = array('agree', 'safe');
		}

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'first_name',
			'last_name',
			'third_name',
			'email',
			'agree',
			'phone',
			'product',
			'channel_id',
		);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('product' => 'Выбери Пакет займов'),
			array('channel_id' => 'Выбери способ получения займа')
		);
	}


	/**
	 * @return bool|void
	 */
	public function beforeValidate()
	{
		$this->_validators = null;

		return parent::beforeValidate();
	}

	/**
	 * Эта функция требуется обязательно перегруженная для того, чтобы в beforeValidate можно было сбрасывать правила
	 * Сделано для того, чтобы в rules() можно было динамически менять правила валидации в зависимости от значений
	 * тех или иных параметров, и перед валидацией пересоздавать правила на основе изменившихся rules()
	 * В обычном случае валидаторы создаются сразу при инициализации модели, когда данные еще не загружены,
	 * потому требуется их пересоздание перед валидацией в данном случае
	 *
	 * @return CList
	 */
	public function getValidatorList()
	{
		if ($this->_validators === null) {
			$this->_validators = $this->createValidators();
		}

		return $this->_validators;
	}

	/**
	 * Эта функция требуется обязательно перегруженная для того, чтобы в beforeValidate можно было сбрасывать правила
	 *
	 * @param null $attribute
	 *
	 * @return array
	 */
	public function getValidators($attribute = null)
	{
		if ($this->_validators === null) {
			$this->_validators = $this->createValidators();
		}

		$validators = array();
		$scenario = $this->getScenario();
		foreach ($this->_validators as $validator) {
			/**
			 * @var CValidator $validator
			 */
			if ($validator->applyTo($scenario)) {
				if ($attribute === null || in_array($attribute, $validator->attributes, true)) {
					$validators[] = $validator;
				}
			}
		}

		return $validators;
	}

}
