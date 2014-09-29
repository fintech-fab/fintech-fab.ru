<?php

/**
 * Class PayForm
 */
class PayForm extends CFormModel
{
	public $sum;
	public $full_pay;

	/**
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('sum', 'numerical', 'min' => 10, 'max'=>Yii::app()->adminKreddyApi->getAbsBalance() ,'allowEmpty' => true, 'tooSmall' => 'Сумма должна быть не менее 10 рублей', 'tooBig' => 'Введенная сумма превышает задолженность, введи сумму еще раз'),
			array('full_pay', 'in', 'range' => [0, 1]),
		);
	}

	public function attributeLabels()
	{
		return array(
			'sum' => 'Сумма',
		);
	}
}
