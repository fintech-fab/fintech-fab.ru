<?php
/**
 * Class SMSPasswordForm
 */
class AddCardForm extends CFormModel
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sCardPan;
	public $sCardMonth;
	public $sCardYear;
	public $sCardCvc;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sCardPan, sCardMonth, sCardYear, sCardCvc', 'required');
		$aRules[] = array(
			'sCardPan', 'match', 'message' => 'Номер карты должен состоять из 16 цифр',
			                     'pattern' => '/^\d{16}$/'
		);
		$aRules[] = array(
			'sCardMonth', 'in', 'range'   => array_keys(Dictionaries::$aMonthsDigital),
			                    'message' => 'Выберите месяц из списка'
		);
		$aRules[] = array(
			'sCardYear', 'in', 'range'   => array_keys(Dictionaries::getYears()),
			                   'message' => 'Выберите год из списка'
		);
		$aRules[] = array(
			'sCardCvc', 'match', 'message' => 'CVC карты должен состоять из 3 цифр',
			                     'pattern' => '/^\d{3}$/'
		);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'sCardPan'   => 'Номер карты',
			'sCardMonth' => 'Месяц',
			'sCardYear'  => 'Год',
			'sCardCvc'   => 'CVC'
		);

	}
}
