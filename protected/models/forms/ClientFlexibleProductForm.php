<?php
/**
 * Class ClientFlexibleProductForm
 *
 */
class ClientFlexibleProductForm extends CFormModel
{
	public $amount;
	public $time;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('amount, time','required'),
			array('amount', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getFlexibleProduct()), 'message' => 'Выберите сумму займа'),
			array('time', 'in', 'range' => array_keys(Yii::app()->adminKreddyApi->getFlexibleProductTime()), 'message' => 'Выберите период займа')
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
			array('amount' => 'Выберите сумму займа',),
			array('time' => 'Выберите период',)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'amount',
			'time',
		);
	}
}
