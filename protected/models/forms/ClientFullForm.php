<?php
/**
 * Class ClientFullForm
 *
 * @method FormFieldValidateBehavior asa()
 *
 */

class ClientFullForm extends ClientCreateFormAbstract
{

	public $complete;
	public $product;

	public function rules()
	{
		$sPhone = Yii::app()->clientForm->getSessionPhone();

		// всегда обязательные поля
		$aRequired = array(
			'first_name',
			'last_name',
			'third_name',

			'birthday',

			'phone',
			'email',

			'sex',

			'passport_series',
			'passport_number',
			'passport_date',
			'passport_issued',
			'passport_code',

			'document',
			'document_number',

			'address_reg_region',
			'address_reg_city',
			'address_reg_address',

			'address_reg_as_res',

			'address_res_region',
			'address_res_city',
			'address_res_address',

			'relatives_one_fio',
			'relatives_one_phone',

			'friends_fio',
			'friends_phone',

			'job_company',
			'job_position',
			'job_phone',
			'job_time',
			'job_monthly_income',
			'job_monthly_outcome',
			'have_past_credit',

			'numeric_code',
			'complete',
			'secret_question',
			'secret_answer',

			'product',
		);

		$aRules =
			array(
				array(
					'phone', 'unique', 'className' => 'ClientData', 'attributeName' => 'phone', 'message' => 'Ошибка! Обратитесь в горячую линию.', 'criteria' => array(
					'condition' => 'complete = :complete AND flag_sms_confirmed = :flag_sms_confirmed', 'params' => array(':complete' => 1, ':flag_sms_confirmed' => 1)
				)),

					array('relatives_one_phone', 'compare', 'operator' => '!=', 'compareValue' => $sPhone, 'message' => 'Номер не должен совпадать с вашим номером телефона!'),
					array('friends_phone', 'compare', 'operator' => '!=', 'compareValue' => $sPhone, 'message' => 'Номер не должен совпадать с вашим номером телефона!'),

					array('friends_phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'relatives_one_phone', 'allowEmpty' => true, 'message' => 'Номер не должен совпадать с телефоном контактного лица.'),
					array('relatives_one_phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'friends_phone', 'allowEmpty' => true, 'message' => 'Номер не должен совпадать с телефоном дополнительного контакта.'),
					array('complete', 'required', 'requiredValue' => 1, 'message' => 'Необходимо подтвердить свое согласие на обработку данных'),
					array('product', 'in', 'range' => array_keys(Dictionaries::$aProducts), 'message' => 'Выберите сумму займа'),


			);
		$aRules = array_merge($aRules, $this->getRulesByFields(
			array(
				'first_name',
				'last_name',
				'third_name',

				'birthday',

				'phone',
				'email',

				'sex',

				'passport_series',
				'passport_number',
				'passport_date',
				'passport_issued',
				'passport_code',

				'document',
				'document_number',

				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'address_reg_as_res',

				'address_res_region',
				'address_res_city',
				'address_res_address',

				'relatives_one_fio',
				'relatives_one_phone',

				'friends_fio',
				'friends_phone',

				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',
				'have_past_credit',

				'numeric_code',
				//'complete',
				'secret_question',
				'secret_answer',

				'product',
			),
			$aRequired
		));

		return $aRules;

	}

	/**
	 * @return bool|void
	 *
	 * Перед валидацией приводим телефон к 10-значному виду, для валидации уникальности по БД
	 */

	public function beforeValidate()
	{
		if ($this->phone) {
			//очистка данных
			$this->phone = ltrim($this->phone, '+ ');
			$this->phone = preg_replace('/[^\d]/', '', $this->phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->phone) == 11) {
				$this->phone = substr($this->phone, 1, 10);
			}
		}
		if ($this->document_number) {
			$this->document_number = mb_strtoupper($this->document_number, 'UTF-8');
		}

		if ($this->relatives_one_phone) {
			//очистка данных
			$this->relatives_one_phone = ltrim($this->relatives_one_phone, '+ ');
			$this->relatives_one_phone = preg_replace('/[^\d]/', '', $this->relatives_one_phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->relatives_one_phone) == 11) {
				$this->relatives_one_phone = substr($this->relatives_one_phone, 1, 10);
			}
		}

		if ($this->friends_phone) {
			//очистка данных
			$this->friends_phone = ltrim($this->friends_phone, '+ ');
			$this->friends_phone = preg_replace('/[^\d]/', '', $this->friends_phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->friends_phone) == 11) {
				$this->friends_phone = substr($this->friends_phone, 1, 10);
			}
		}

		return parent::beforeValidate();
	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'friends_fio'     => 'ФИО',
				'friends_phone'   => 'Телефон',

				'complete'        => 'Согласен с условиями и передачей данных (<a data-toggle="modal" href="#privacy">подробная информация</a>)',
				'secret_question' => 'Секретный вопрос',
				'secret_answer'   => 'Ответ на секретный вопрос',

				'product'         => 'Сумма займа',

			)
		);
	}
}

?>