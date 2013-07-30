<?php
/**
 * Контактные данные:
 * + Телефон
 * + Электронная почта
 * Личные данные:
 * + Фамилия
 * + Имя
 * + Отчество
 * + Дата рождения
 * + Пол
 * Паспортные данные:
 * + Серия / номер
 * + Когда выдан
 * + Кем выдан
 * + Код подразделения
 * Второй документ:
 * + Название
 * + Номер
 *
 * Class ClientCreateForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientPersonalDataForm extends ClientCreateFormAbstract
{

	/*public $passport_issued; // кем выдан паспорт

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
	*/

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


				'phone',
				'email',

				'first_name',
				'last_name',
				'third_name',

				'birthday',
				'sex',

				'passport_series',
				'passport_number',
				'passport_date',
				'passport_issued',
				'passport_code',

				'document',
				'document_number',

			),
			$aRequired
		);

		return $aRules;

	}

}