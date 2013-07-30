<?php
/**
 * Форма данных о работе
 *
 * = Поля формы =
 * Информация о работе:
 * - Место работы (название компании)
 * - Должность
 * - Номер телефона
 * - Стаж работы
 * - Среднемесячный доход
 * - Среднемесячный расход
 * - Наличие кредитов и займов в прошлом
 *
 * Class ClientJobInfoForm
 *
 * @method FormFieldValidateBehavior asa()
 */
class ClientJobInfoForm extends ClientCreateFormAbstract
{

	public function rules()
	{

		// всегда обязательные поля
		$aRequired = array_merge(
			array(
				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',
				'have_past_credit',
			),
			$this->getCommonRequires()
		);

		$aRules = $this->getRulesByFields(

			array(
				'job_company',
				'job_position',
				'job_phone',
				'job_time',
				'job_monthly_income',
				'job_monthly_outcome',
				'have_past_credit',
			),
			$aRequired
		);

		return $aRules;

	}

}