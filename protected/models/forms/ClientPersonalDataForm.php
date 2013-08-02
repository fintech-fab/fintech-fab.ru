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
			)
		);

		$aRules =
			array(
				array('phone', 'unique', 'className'=>'ClientData', 'attributeName'=>'phone','message'=>'Ошибка! Позвоните, пожалуйста, на горячую линию.','criteria'=>array(
						'condition'=>'complete = :complete','params' => array(':complete'=>1)
					)
				),
			);
		$aRules = array_merge($aRules, $this->getRulesByFields(
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
		));
		return $aRules;

	}

	public function beforeValidate()
	{
		if ($this->phone)
		{
			//очистка данных
			$this->phone = ltrim( $this->phone, '+ ' );
			$this->phone = preg_replace('/[^\d]/', '', $this->phone);

			// убираем лишний знак слева (8-ка или 7-ка)
			if(strlen($this->phone) == 11){
				$this->phone = substr($this->phone,1,10);
			}
		}
		return parent::beforeValidate();
	}
}