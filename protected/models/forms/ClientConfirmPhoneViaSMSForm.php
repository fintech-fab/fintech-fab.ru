<?php
/**
 * Class ClientConfirmPhoneViaSMSForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientConfirmPhoneViaSMSForm extends ClientCreateFormAbstract
{
	/**
	 * @var boolean заполненность формы
	 */
	public $sms_code;

	/**
	 * @var int число попыток ввода кода
	 */
	public $iCountTries;

	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sms_code', 'required', 'message' => 'Поле обязательно к заполнению');
		$aRules[] = array('sms_code', 'length','max'=>6, 'message' => 'Поле обязательно к заполнению');
		$aRules[] = array('sms_code', 'match', 'message' => 'SMS-код состоит из 6 цифр', 'pattern' => '/^\d{6}$/');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array('sms_code' => 'Код подтверждения из SMS',)
		);
	}
}
