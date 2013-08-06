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

	public function init()
	{
		$aClientConfirmPhoneViaSMSFormSession = Yii::app()->session['ClientConfirmPhoneViaSMSForm'];

		if(!array_key_exists('iCountTries',$aClientConfirmPhoneViaSMSFormSession)){
			$aClientConfirmPhoneViaSMSFormSession['iCountTries'] = 0;
			Yii::app()->session['ClientConfirmPhoneViaSMSForm'] = $aClientConfirmPhoneViaSMSFormSession;
		}
	}

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
