<?php
/**
 * Форма персональных данных
 *
 * = Поля формы =
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
 * Class ClientPersonalDataForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientPersonalDataForm extends ClientCreateFormAbstract
{

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'email',
				'passport_issued',
				'document',
				'document_number',
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