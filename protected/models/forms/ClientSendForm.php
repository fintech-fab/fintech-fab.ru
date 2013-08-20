<?php
/**
 * Class ClientSendForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientSendForm extends ClientCreateFormAbstract
{
	/**
	 * @var boolean заполненность формы
	 */
	public $complete;

	public function rules()
	{
		$aRequired = array(
			'numeric_code',
			'complete',
			'secret_question',
			'secret_answer',
		);

		$aRules = $this->getRulesByFields(

			array(
				'numeric_code',
				'secret_question',
				'secret_answer',
			),
			$aRequired
		);
		$aRules[] = array('complete', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие на обработку данных');

		return $aRules;

	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'complete'        => 'Я подтверждаю достоверность данных и даю согласие на их обработку (<a data-toggle="modal" href="#privacy">подробная информация</a>)',
				'secret_question' => 'Секретный вопрос',
				'secret_answer'   => 'Ответ на секретный вопрос',
			)
		);
	}
}
