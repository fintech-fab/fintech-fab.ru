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
		if(!isset(Yii::app()->session['sms_count_tries'])){
			Yii::app()->session['sms_count_tries']=0;
		}

		if(!isset(Yii::app()->session['flag_sms_sent'])){
			Yii::app()->session['flag_sms_sent']=false;
		}
	}

	public function rules()
	{
		$aRules = array();

		$aRules[] = array('sms_code', 'required', 'message' => 'Поле обязательно к заполнению');
		$aRules[] = array('sms_code', 'match', 'message' => 'SMS-код состоит из '.SiteParams::C_SMSCODE_LENGTH.' цифр',
			'pattern' => '/^\d{'.SiteParams::C_SMSCODE_LENGTH.'}$/');

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
