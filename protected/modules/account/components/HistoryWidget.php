<?php
Yii::import('bootstrap.widgets.TbGridView');

/**
 * Class HistoryWidget
 */
class HistoryWidget extends TbGridView
{
	public $type = 'striped bordered condensed';
	public $emptyText = 'История операций пуста';

	const C_INVOICE_TYPE_SUBSCRIPTION = 1;
	const C_INVOICE_TYPE_LOAN = 2;
	const C_INVOICE_TYPE_LOAN_FINE = 3;
	const C_INVOICE_TYPE_LOAN_PENALTY = 4;
	const C_INVOICE_TYPE_LOAN_FINE_PENALTY = 5;

	const C_INVOICE_TYPE_LOAN_SUBSCRIPTION = 6;
	const C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE = 7;
	const C_INVOICE_TYPE_LOAN_SUBSCRIPTION_PENALTY = 8;
	const C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE_PENALTY = 9;
	const C_INVOICE_TYPE_SUBSCRIPTION_POSTPAID = 10;

	const PRODUCT_TYPE_KREDDY = 1;
	const PRODUCT_TYPE_IVANOVO = 2;
	const PRODUCT_TYPE_KREDDYLINE = 3;
	const PRODUCT_TYPE_KREDDYLINE_POSTPAID = 4;
	const PRODUCT_TYPE_KREDDYLINE_PERCENT_PREPAID = 5;
	const PRODUCT_TYPE_KREDDYLINE_PERCENT_POSTPAID = 6;

	protected static $aOperationTypes = array(
		self::PRODUCT_TYPE_KREDDY                      => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION      => 'Оплата Пакета',
			self::C_INVOICE_TYPE_LOAN              => 'Возврат суммы перевода',
			self::C_INVOICE_TYPE_LOAN_FINE         => 'Частичное погашение задолженности (пени, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_PENALTY      => 'Частичное погашение задолженности (штраф, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_FINE_PENALTY => 'Частичное погашение задолженности (штраф, пени, сумма перевода)',
		),
		self::PRODUCT_TYPE_IVANOVO                     => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION      => 'Оплата займа',
			self::C_INVOICE_TYPE_LOAN              => 'Погашение займа',
			self::C_INVOICE_TYPE_LOAN_FINE         => 'Частичное погашение задолженности (пени, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_PENALTY      => 'Частичное погашение задолженности (штраф, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_FINE_PENALTY => 'Частичное погашение задолженности (штраф, пени, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_FINE_PENALTY => 'Частичное погашение задолженности (штраф, пени, сумма перевода)',
		),
		self::PRODUCT_TYPE_KREDDYLINE                  => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION      => 'Абонентская плата',
			self::C_INVOICE_TYPE_LOAN              => 'Возврат суммы перевода',
			self::C_INVOICE_TYPE_LOAN_FINE         => 'Частичное погашение задолженности (пени, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_PENALTY      => 'Частичное погашение задолженности (штраф, сумма перевода)',
			self::C_INVOICE_TYPE_LOAN_FINE_PENALTY => 'Частичное погашение задолженности (штраф, пени, сумма перевода)',
		),
		self::PRODUCT_TYPE_KREDDYLINE_POSTPAID         => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION_POSTPAID          => 'Абонентская плата',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION              => 'Абонентская плата и сумма перевода',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE         => 'Абонентская плата, сумма перевода и пени',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_PENALTY      => 'Абонентская плата, сумма перевода и штраф',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE_PENALTY => 'Абонентская плата, сумма перевода, штраф и пени',
		),
		self::PRODUCT_TYPE_KREDDYLINE_PERCENT_PREPAID  => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION      => 'Внесение абонентской платы',
			self::C_INVOICE_TYPE_LOAN              => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_FINE         => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_PENALTY      => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_FINE_PENALTY => 'Погашение задолженности',
		),
		self::PRODUCT_TYPE_KREDDYLINE_PERCENT_POSTPAID => array(
			self::C_INVOICE_TYPE_SUBSCRIPTION_POSTPAID          => 'Внесение абонентской платы',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION              => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE         => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_PENALTY      => 'Погашение задолженности',
			self::C_INVOICE_TYPE_LOAN_SUBSCRIPTION_FINE_PENALTY => 'Погашение задолженности',
		),
	);

	const LOAN_TRANSFER_MESSAGE = 'Получение денег';

	public function run()
	{
		$this->setColumns();
		$this->initColumns();

		parent::run();
	}

	/**
	 * @return array
	 */
	private function setColumns()
	{
		$this->columns = array(
			array(
				'name'   => 'time',
				'header' => ('Дата и время ' . CHtml::tag('i',
							array(
								"class" => "icon-question-sign",
								"rel"   => "tooltip",
								"title" => Dictionaries::C_INFO_MOSCOWTIME
							)
						)
					),
				'value'  => 'date("d.m.Y H:i",strtotime($data["time"]))',
			),
			array(
				'name'   => 'type_id',
				'header' => 'Операция',
				'value'  => '$this->grid->formatOperation($data)',
			),
			array(
				'name'   => 'amount',
				'header' => 'Сумма',
				'type'   => 'raw',
				'value'  => '($data["type"]=="invoice")?"<span>".abs($data["amount"])."</span>":"<span style=\"color: green\">".abs($data["amount"])."</span>"',
			),
		);
	}

	/**
	 * @param $aData
	 *
	 * @return string
	 */
	public function formatOperation($aData)
	{
		$sType = $aData['type'];
		$iTypeId = $aData['type_id'];
		$iProductTypeId = $aData['product_type_id'];

		return ($sType == "invoice") ? self::$aOperationTypes[$iProductTypeId][$iTypeId] : self::LOAN_TRANSFER_MESSAGE;
	}
}