<?php
/**
 * Class AddCardForm
 */
/* @method FormFieldValidateBehavior asa() */

class AddCardForm extends CFormModel
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sCardPan;
	public $sCardMonth;
	public $sCardYear;
	public $sCardCvc;
	public $bConfirm;
	public $iCardType;

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('sCardPan, sCardMonth, sCardYear, sCardCvc, iCardType', 'required'),
			array('iCardType', 'required', 'message' => 'Выберите тип карты Mastercard либо Maestro'),

			array('bConfirm', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие.'),

			array(
				'sCardPan', 'match', 'message' => 'Номер карты должен содержать от 16 до 20 цифр',
				                     'pattern' => '/^\d{16,20}$/'
			),
			array(
				'sCardPan', 'checkValidCardPan', 'iCardType' => 'iCardType', 'message' => 'Номер карты неправильный. Проверьте тип выбранной карты и ее номер.',
			),
			array(
				'sCardMonth', 'in', 'range'   => array_keys(Dictionaries::$aMonthsDigital),
				                    'message' => 'Выберите месяц из списка'
			),
			array(
				'sCardYear', 'in', 'range'   => array_keys(Dictionaries::getYears()),
				                   'message' => 'Выберите год из списка'
			),
			array(
				'sCardCvc', 'match', 'message' => 'CVC карты должен состоять из 3 цифр',
				                     'pattern' => '/^\d{3}$/'
			),

			array(
				'iCardType', 'in', 'range' => array_keys(Dictionaries::$aCardTypes), 'message' => 'Выберите тип карты Mastercard либо Maestro'
			),
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
			'sCardMonth' => 'Срок окончания',
			'sCardYear'  => 'Год',
			'sCardCvc'   => 'Код CVC',
			'bConfirm'  => 'Я подтверждаю согласие на блокировку случайной суммы на указанной банковской карте.',
			'iCardType' => 'Тип банковской карты',
		);

	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'sCardPan',
			'sCardMonth',
			'sCardYear',
			'sCardCvc',
			'iCardType',
		);
	}

	/**
	 * проверка PAN карты
	 *
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidCardPan($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidCardPan($attribute, $param);
	}

	/**
	 * подключаем общий помощник по валидации разных данных
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'FormFieldValidateBehavior' => array(
				'class' => 'application.extensions.behaviors.FormFieldValidateBehavior',
			),
		);
	}

}
