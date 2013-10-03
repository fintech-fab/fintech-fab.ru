<?php
/**
 * Class VerifyCardForm
 */

class VerifyCardForm extends CFormModel
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sCardVerifyAmount;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sCardVerifyAmount', 'required');
		$aRules[] = array(
			'sCardVerifyAmount', 'type','type'=>'float','allowEmpty'=>false , 'message' => 'Сумма должна быть числом от 1.01 до 4.99',);

		return $aRules;
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'sCardVerifyAmount'   => 'Замороженная сумма',
		);

	}

	/**
	 * @return bool
	 */
	protected function beforeValidate()
	{
		if(isset($this->sCardVerifyAmount)){
			$this->sCardVerifyAmount = str_replace(',','.',$this->sCardVerifyAmount);
			$this->sCardVerifyAmount = floatval($this->sCardVerifyAmount);
		}
		return parent::beforeValidate();
	}
}
