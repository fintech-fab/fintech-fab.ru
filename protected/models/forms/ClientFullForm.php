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
	public $password;
	public $password_repeat;
	public $subscribe_news; // галочка "получать новости сервиса" todo behaviuors для новых полей
	public $status; // статус
	public $income_source; // источник дохода
	public $educational_institution_name; // название учебного заведения
	public $educational_institution_phone; // телефон учебного заведения
	public $goal; // цель займа

	/**
	 * @return array
	 */
	public function rules()
	{
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

			'relatives_one_fio',
			'relatives_one_phone',

			'status',
			'have_past_credit',
			'goal',

			'numeric_code',
			'complete',
			'secret_question',
			'secret_answer',
			'password',
			'password_repeat',
		);

		$aMyRules =
			array(
				array('phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'relatives_one_phone', 'message' => 'Номер не должен совпадать с телефоном контактного лица!'),
				array('phone', 'compare', 'operator' => '!=', 'compareAttribute' => 'friends_phone', 'message' => 'Номер не должен совпадать с телефоном дополнительного контакта!'),
			);
		$aRules = array_merge($this->getRulesByFields(
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

				'status',
				'income_source',
				'educational_institution_name',
				'educational_institution_phone',

				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',
				'have_past_credit',

				'numeric_code',
				'secret_question',
				'secret_answer',
				'password',
				'password_repeat',
				'goal',
			),
			$aRequired
		), $aMyRules);

		return $aRules;

	}

	/**
	 * @return bool|void
	 *
	 * Перед валидацией приводим телефон к 10-значному виду, для валидации уникальности по БД
	 */

	protected function beforeValidate()
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

		if ($this->job_phone) {
			//очистка данных
			$this->job_phone = ltrim($this->job_phone, '+ ');
			$this->job_phone = preg_replace('/[^\d]/', '', $this->job_phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if (strlen($this->job_phone) == 11) {
				$this->job_phone = substr($this->job_phone, 1, 10);
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

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'relatives_one_fio'             => 'ФИО',
				'relatives_one_phone'           => 'Телефон',

				'friends_fio'                   => 'ФИО',
				'friends_phone'                 => 'Телефон',

				'complete'                      => 'Я подтверждаю достоверность введенных данных и даю согласие на их обработку (<a data-toggle="modal" href="#privacy">подробная информация</a>)',
				'subscribe_news'                => 'Я согласен/согласна получать новости от kreddy.ru (<a data-toggle="modal" href="#privacy">подробная информация</a>)', //todo: ссылка на модальное окно?

				'passport_number'               => 'Серия/номер',
				'passport_series'               => 'Серия/номер',

				'secret_question'               => 'Секретный вопрос',
				'secret_answer'                 => 'Ответ на секретный вопрос',

				'product'                       => 'Сумма займа',
				'address_reg_as_res'            => 'фактический адрес совпадает с пропиской',

				'status'                        => 'Статус',
				'educational_institution_name'  => 'Название учебного заведения',
				'educational_institution_phone' => 'Телефон учебного заведения',
				'job_monthly_income'            => 'Среднемесячный доход',
				'job_monthly_outcome'           => 'Среднемесячный расход',
				'income_source'                 => 'Источник дохода',

				'have_past_credit'              => 'Наличие кредитов и займов в прошлом',

				'password'                      => 'Пароль для входа в личный кабинет',
				'password_repeat'               => 'Подтверждение пароля',

				'goal'                          => 'Цель займа',

			)
		);
	}


	/**
	 * @return array
	 */
	public function attributeNames()
	{
		return array(
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

			'status',
			'educational_institution_name',
			'educational_institution_phone',
			'job_company',
			'job_position',
			'job_phone',
			'job_time',
			'job_monthly_income',
			'job_monthly_outcome',
			'income_source',
			'have_past_credit',

			'goal',
			'numeric_code',
			'secret_question',
			'secret_answer',
			'complete',
			'subscribe_news',
			'password',
			'password_repeat',
		);
	}

}

