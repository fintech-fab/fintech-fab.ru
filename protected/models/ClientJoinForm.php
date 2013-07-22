<?php

/**
 * Class ClientJoinForm
 * класс для первой формы данных клиента
 *
 * @method
 * FormFieldValidateBehavior asa()
 */

class ClientJoinForm extends CFormModel {

    public $phone; // телефон

    public function rules()
    {

            return array(
                array($this->getCommonRequires(), 'required','message'=>'Поле {attribute} не может быть пустым.'),
                array('phone', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Неверный формат телефона, пример верного номера: +71234567890'),
                //array('phone', 'match', 'pattern' => '/^(\+7)\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/', 'message' => 'Неверный формат телефона, пример верного номера: +7(123)456-78-90'),
            );

    }

    protected function getCommonRequires()
    {
        $aRequires =  array(
            'phone',
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

            'phone' => 'Мобильный телефон',

        );
    }


}