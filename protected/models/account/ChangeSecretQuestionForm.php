<?php
/**
 * Class ChangeSecretQuestionForm
 */

class ChangeSecretQuestionForm extends ClientFullForm
{

	/**
	 * @return array
	 */
	public function rules()
	{
		// всегда обязательные поля
		$aRequired = array(
			'secret_question',
			'secret_answer'
		);
		$aMyRules =
			array();
		$aRules = array_merge($this->getRulesByFields(
			array(
				'secret_question',
				'secret_answer'
			),
			$aRequired
		), $aMyRules);

		return $aRules;

	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(

			)
		);
	}

	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
			'secret_question',
			'secret_answer'
		);
	}
}

