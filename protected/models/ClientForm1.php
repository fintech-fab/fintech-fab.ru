<?php

/**
 * Class ClientForm1
 * класс для первой формы данных клиента
 *
 * @method
 * FormFieldValidateBehavior asa()
 */

class ClientForm1 extends CFormModel {

    public $first_name; // имя
    public $last_name; // фамилия
    public $third_name; // отчество
    public $birthday; // день (дата) рождения

    public $sex; // пол

    public $passport_series; // серия паспорта
    public $passport_number; // номер паспорта
    public $passport_date; // когда выдан
    public $passport_code; // код подразделения @new

    public function rules()
    {
        $aSexes=array("0"=>"Мужской","1"=>"Женский");
        return array(
            // username and password are required
            array($this->getCommonRequires(), 'required'),
            array('first_name', 'match','pattern'=>'/^[а-яё]+$/ui', 'message' => 'Имя может содержать только русские буквы'),
            array('last_name', 'match','pattern'=>'/^[а-яё]+$/ui', 'message' => 'Фамилия может содержать только русские буквы'),
            array('third_name', 'match','pattern'=>'/^[а-яё]+$/ui', 'message' => 'Отчество может содержать только русские буквы'),
            array('passport_series', 'match', 'pattern' => '/^\d{4}$/','message' => 'Серия паспорта должна состоять из четырех цифр'),
            array('passport_number', 'match', 'pattern' => '/^\d{6}$/','message' => 'Номер паспорта должен состоять из шести цифр'),
            array('passport_code', 'match', 'pattern' => '/^\d{3}\-\d{3}$/', 'message' => 'Неверный формат кода, пример верного кода: 123-456'),
            array('sex', 'in', 'range' => array_keys($aSexes),'message' => 'Укажите пол'),
            array('sex', 'required','message' => 'Укажите пол'),
            array('birthday', 'date', 'message' => 'Введите корректное значение для даты', 'format' => 'yyyy-mm-dd'),
            array('passport_date', 'date', 'message' => 'Введите корректное значение для даты', 'format' => 'yyyy-mm-dd'),
			array('first_name, last_name, third_name', 'length', 'max'=>255),
        );

    }

    protected function getCommonRequires()
    {
        $aRequires =  array(
            'first_name', 'last_name', 'third_name', // фио
            'birthday', // пол, др
            'passport_series', 'passport_number', 'passport_date', 'passport_code', // паспорт
       );

        return $aRequires;
    }

    /**
     * названия атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return array(

            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'third_name' => 'Отчество',

            'sex' => 'Пол',
            'birthday' => 'День рождения',

            'passport_series' => 'Серия',
            'passport_number' => 'Номер',
            'passport_date' => 'Дата выдачи',
            'passport_code' => 'Код подразделения',

        );
    }

	protected function afterValidate()
	{
		$p = new CHtmlPurifier;
		$p->options = array(
			'HTML.SafeObject'=>true,
		);
		$attr=$this->getAttributes();
		foreach($attr as &$a)
		{
			$a=$p->purify($a);
		}
		unset($a);
		$this->setAttributes($attr);

		return parent::afterValidate();
	}

}