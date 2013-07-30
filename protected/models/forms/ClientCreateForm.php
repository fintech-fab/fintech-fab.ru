<?php
/**
 * Class ClientCreateForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientCreateForm extends ClientCreateFormAbstract
{

	public $entry_point_id; //Источник анкеты
	public $prev_first_name; // предыдущее имя
	public $prev_third_name; // предыдущее отчество
	public $passport_issued; // кем выдан паспорт

	public $citizenship = 1; //гражданиство

	public $job_monthly_income; //месячный доход
	public $job_monthly_outcome; // месячный расход

	public $job_director_name; // фио директор на работе
	public $job_director_phone; // телефон директора на работе

	public $liabilities; // дополнительные обязатльства

	public $birthplace_country; // страна рождения
	public $birthplace_city; // город рождения

	public $education; // образование
	public $inn; // инн

	public $have_car = 0; // есть автомобиль
	public $have_estate = 0; // есть недвижимость
	public $have_credit = 0; // есть кредит

	public $address_reg_date; // дата регистрации
	public $address_reg_street; // регистрация: улица
	public $address_reg_house; // регистрация: дом
	public $address_reg_build; // регистрация: корпус/строение
	public $address_reg_apart; // регистрация: квартира

	public $address_res_street; // проживание: улица
	public $address_res_house; // проживание: дом
	public $address_res_build; // проживание: корпус/строение
	public $address_res_apart; // проживание: квартира

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'entry_point_id',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(

				'entry_point_id',

				'first_name',
				'last_name',
				'third_name',

				'sex',
				'prev_last_name',
				'birthday',

				'passport_series',
				'passport_number',
				'passport_date',
				'passport_issued',
				'passport_code',
				'passport_issued',

				'document',
				'document_number',

				'address_reg_region',
				'address_reg_city',
				'address_reg_address',

				'address_res_region',
				'address_res_city',
				'address_res_address',

				'phone',
				'email',
				'numeric_code',

				'job_company',
				'job_position',
				'job_phone',

				'have_past_credit',
				'relatives_one_fio',
				'relatives_one_phone',

				// поля для коллекторов

				'address_reg_post_index',
				'address_res_post_index',
				'phone_home',
				'job_time',

				'friends_fio',
				'friends_phone',

				'relatives_degree',
				'relatives_fio',
				'relatives_phone',

				'job_director_name',
				'job_director_phone',

				'marital_status',
				'have_dependents',

				'job_less',
				'job_salary_date',
				'job_prepay_date',
				'job_income_add',

				// старые поля

				'job_monthly_income',
				'job_monthly_outcome',

				'prev_first_name',
				'prev_third_name',

				'education',
				'inn',
				'citizenship',

				'job_director_phone',
				'job_director_name',

				'liabilities',

				'birthplace_country',
				'birthplace_city',

				'have_car',
				'have_estate',
				'have_credit',

				'address_reg_date',
				'address_reg_street',
				'address_reg_house',
				'address_reg_build',
				'address_reg_apart',

				'address_res_street',
				'address_res_house',
				'address_res_build',
				'address_res_apart',

			),
			$aRequired
		);

		return $aRules;

	}

}