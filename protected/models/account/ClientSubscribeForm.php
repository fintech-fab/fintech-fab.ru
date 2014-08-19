<?php

/**
 * Class ClientSubscribeForm
 *
 */
class ClientSubscribeForm extends ClientCreateFormAbstract
{
	public $product;

	public $loan_amount;
	public $product_type;

	public $registry_agree;
	public $not_public_agree;
	public $my_interests_agree;
	public $auto_debiting_agree;

	const C_POST_PAID = 'post_paid';
	const C_PRE_PAID = 'pre_paid';

	public static $aProductTypes = array(
		self::C_PRE_PAID  => 'сразу',
		self::C_POST_PAID => 'в течение 30 дней',
	);

	public $aLoanAmounts = array();

	public function getLoanAmounts()
	{
		if (!empty($this->aLoanAmounts)) {
			return $this->aLoanAmounts;
		}

		$aLoanAmounts = array_unique(Yii::app()->adminKreddyApi->getClientProductsList());

		ksort($aLoanAmounts);

		foreach ($aLoanAmounts as $iLoanAmount) {
			$this->aLoanAmounts[$iLoanAmount] = $iLoanAmount . ' Р';
		}

		return $this->aLoanAmounts;
	}

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('product', 'required', 'message' => ''),
			array('product', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getClientProductsList()), 'message' => 'Для подключения сервиса требуется выбрать продукт'),
			array('loan_amount, product_type', 'required', 'message' => 'Необходимо выбрать {attribute}', 'on' => 'allValidate'),
			array('loan_amount', 'in', 'range' => array_values(array_unique(Yii::app()->adminKreddyApi->getClientProductsList())), 'message' => 'Выбери сумму', 'on' => 'allValidate'),
			array('product_type', 'in', 'range' => array_keys(self::$aProductTypes), 'message' => 'Выбери способ оплаты абонентки', 'on' => 'allValidate'),
			array('registry_agree, not_public_agree, my_interests_agree, auto_debiting_agree', 'required', 'requiredValue' => 1, 'message' => 'Не отмечено "{attribute}"', 'on' => 'allValidate'),
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'product'             => 'Выбери канал',
				'loan_amount'         => 'сумму',
				'product_type'        => 'тип продукта',
				'registry_agree'      => 'Я ознакомлен со сведениями в внесении ООО "Финансовые решения" в
				                          государтвенный реестр МФО <a data-toggle="modal" href="#registry_view">[посмотреть]</a>
				                          ',
				'not_public_agree'    => 'Я не являюсь публичным лицом или лицом, указанным в перечне <a data-toggle="modal" href="#not_public_view">[посмотреть]</a>',
				'my_interests_agree'  => 'Я действую в личных интересах  <a data-toggle="modal" href="#my_interests_view">[посмотреть]</a>',
				'auto_debiting_agree' => 'Я согласен на списание задолженности с моей банковской карты при нарушении условий Договора  <a data-toggle="modal" href="#auto_debiting_view">[посмотреть]</a>',
			)
		);
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

	/**
	 * Из product получаем product_type и loan_amount
	 */
	public function setAttributesByProduct()
	{
		if (!$this->product) {
			return;
		}

		$this->loan_amount = Yii::app()->adminKreddyApi->getProductLoanAmountById($this->product);

		if (array_key_exists($this->product, Yii::app()->adminKreddyApi->getClientProductsList(true, false))) {
			$this->product_type = self::C_POST_PAID;
		} else {
			$this->product_type = self::C_PRE_PAID;
		}
	}

	/**
	 * Из product_type и loan_amount получаем product
	 */
	public function setProductByAttributes()
	{
		$this->validate();

		if ($this->getError('product_type') != '' || $this->getError('loan_amount') != '') {
			return;
		}

		if ($this->product_type == self::C_POST_PAID) {
			$aProducts = Yii::app()->adminKreddyApi->getClientProductsList(true, false);
		} else {
			$aProducts = Yii::app()->adminKreddyApi->getClientProductsList(false, true);
		}

		$aProductsFlipped = array_flip($aProducts);

		if (isset($aProductsFlipped[$this->loan_amount])) {
			$this->product = $aProductsFlipped[$this->loan_amount];
		}

	}
}
