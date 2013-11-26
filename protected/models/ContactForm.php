<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
	public $name;
	public $email;
	public $subject;
	public $body;
	public $verifyCode;
	public $phone;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, phone, email, subject, body, verifyCode', 'required'),
			array('subject', 'in', 'range' => array_keys(Dictionaries::$aSubjectsQuestions), 'message' => 'Укажите тему вопроса'),
			// email has to be a valid email address
			array('email', 'email'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty' => false),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'name'       => 'Имя',
			'email'      => 'E-Mail',
			'subject'    => 'Тема вопроса',
			'body'       => 'Вопрос',
			'phone'      => 'Телефон',
			'verifyCode' => 'Введите текст с картинки',
		);
	}
}
