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
	public $sCardHolderName;
	public $sCardCvc;
	public $bConfirm;
	public $iCardType;

	public $sCardValidThru; // срок окончания: формат 09 / 15, из него потом берётся $sCardMonth, $sCardYear

	/**
	 * @return array
	 */
	public function rules()
	{
		$aRules = array(
			array('sCardPan, sCardValidThru, sCardHolderName, sCardCvc', 'required'),
			array('iCardType', 'required', 'message' => 'Выберите тип карты Mastercard либо Maestro'),

			array('bConfirm', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие.'),

			array(
				'sCardPan', 'match', 'message' => 'Номер карты должен содержать от 16 до 18 цифр',
				                     'pattern' => '/^\d{16,18}$/'
			),
			array(
				'sCardPan', 'checkValidCardPan', 'iCardType' => 'iCardType', 'message' => 'Номер карты неправильный. Проверьте тип выбранной карты и ее номер.',
			),
			array(
				'sCardHolderName', 'match', 'message' => 'Имя держателя не должно содержать цифр и русских букв',
				                            'pattern' => '/^[^а-яё0-9]+$/ui'
			),
			array(
				'sCardValidThru', 'checkValidCardValidThru', 'messageInvalidMonth' => 'Проверьте срок действия карты (некорректно указан месяц)',
				                                             'messageInvalidYear'  => 'Проверьте срок действия карты (некорректно указан год)',
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
		//'bConfirm'  => 'Я подтверждаю правильность введенных мною данных.',

		$aLabels = array(
			'sCardPan'        => 'Номер карты',
			'sCardValidThru' => 'Срок действия карты',
			'sCardMonth'     => 'Месяц',
			'sCardYear'       => 'Год',
			'sCardCvc'        => 'Код CVC',
			'sCardHolderName' => 'Имя держателя',
			'bConfirm'        => 'Я подтверждаю согласие на блокировку случайной суммы на указанной банковской карте.',
			'iCardType'       => 'Тип банковской карты',
		);

		if (!Yii::app()->adminKreddyApi->checkCardVerifyExists()) {
			$aLabels['bConfirm'] = 'Я подтверждаю правильность введенных мною данных.';
		}

		return $aLabels;
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'sCardPan',
			'sCardValidThru',
			'sCardMonth',
			'sCardYear',
			'sCardCvc',
			'sCardHolderName',
			'iCardType',
		);
	}

	/**
	 * перед валидацией приводим поле "Имя держателя" к верхнему регистру и убираем пробелы с номера карты
	 */
	public function beforeValidate()
	{
		if (!parent::beforeValidate()) {
			return false;
		}

		if (!empty($this->sCardHolderName)) {
			$this->sCardHolderName = trim($this->sCardHolderName);
			$this->sCardHolderName = mb_convert_case($this->sCardHolderName, MB_CASE_UPPER, 'utf-8');
		}

		if (!empty($this->sCardPan)) {
			$this->sCardPan = trim($this->sCardPan);
		}

		return true;
	}

	/**
	 * после валидации получаем поля месяц и год окончания
	 */
	public function afterValidate()
	{
		if (!empty($this->sCardValidThru)) {
			list($sMonth, $sYear) = explode("/", $this->sCardValidThru);

			$this->sCardMonth = trim($sMonth);
			$this->sCardYear = trim($sYear);
		}

		parent::afterValidate();
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
	 * проверка корректности срока окончания
	 *
	 * @param $attribute
	 * @param $param
	 */
	public function checkValidCardValidThru($attribute, $param)
	{
		$this->asa('FormFieldValidateBehavior')->checkValidCardValidThru($attribute, $param);
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
