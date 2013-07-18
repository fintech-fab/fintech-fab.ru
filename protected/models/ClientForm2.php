<?php

/**
 * Class ClientForm2
 * класс для первой формы данных клиента
 *
 * @method
 * FormFieldValidateBehavior asa()
 */

class ClientForm2 extends CFormModel {

    public $document; // второй документ
    public $document_number; // номер второго документа

    public $address_reg_region; // Республика/край/область*
    public $address_reg_city; // Населенный пункт (Город, поселок, деревня и т.д.)*
    public $address_reg_address; // Адрес (Улица, дом, корпус/строение, квартира)*

    public $phone; // телефон
    public $email; // электронная почта

    public function rules()
    {
            $aDocuments=array("Заграничный паспорт"=>"Заграничный паспорт",
                                "Водительское удостоверение"=>"Водительское удостоверение",
                                "Пенсионное удостоверение"=>"Пенсионное удостоверение",
                                "Военный билет"=>"Военный билет",
                                "Свидетельство ИНН"=>"Свидетельство ИНН",
                                "Страховое свидетельство государственного пенсионного страхования"=>"Страховое свидетельство государственного пенсионного страхования"
                            );
            return array(
                array($this->getCommonRequires(), 'required','message'=>'Поле {attribute} не может быть пустым.'),
                array('document', 'in', 'range' => array_keys($aDocuments),'message' => 'Выберите документ из списка'),
                array('email', 'email', 'message' => 'Введите email в правильном формате'),
                array('phone', 'match', 'pattern' => '/^\d{10}$/', 'message' => 'Неверный формат телефона, пример верного номера: +71234567890'),
                //array('phone', 'match', 'pattern' => '/^(\+7)\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/', 'message' => 'Неверный формат телефона, пример верного номера: +7(123)456-78-90'),
            );

    }

    public function afterValidate(){
        /*$this->phone=str_replace('+7','',$this->phone);
        $this->phone=str_replace('+7','',$this->phone);
        $this->phone=str_replace('(','',$this->phone);
        $this->phone=str_replace(')','',$this->phone);
        $this->phone=str_replace('-','',$this->phone);

        /*$model->phone=substr_replace($model->phone,'+7',0,0);
        $model->phone=substr_replace($model->phone,'(',2,0);
        $model->phone=substr_replace($model->phone,')',6,0);
        $model->phone=substr_replace($model->phone,'-',10,0);
        $model->phone=substr_replace($model->phone,'-',13,0);
        echo $model->phone;
        */
        return parent::afterValidate();
    }

    protected function getCommonRequires()
    {
        $aRequires =  array(
            'document', 'document_number',//второй документ
            'address_reg_region','address_reg_city','address_reg_address', // адрес
            'phone', 'email', //контакты
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

            'document' => 'Тип документа',
            'document_number' => 'Номер документа',

            'address_reg_region' => 'Регион (республика/край/область)',
            'address_reg_city' => 'Населенный пункт (город, поселок, деревня)',
            'address_reg_address' => 'Адрес (улица, дом, корпус/строение, квартира',

            'phone' => 'Мобильный телефон',
            'email' => 'Email',
        );
    }


}