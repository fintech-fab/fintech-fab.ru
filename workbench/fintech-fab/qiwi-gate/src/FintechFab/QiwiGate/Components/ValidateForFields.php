<?php
namespace FintechFab\QiwiGate\Components;


class ValidateForFields
{

	public static function messagesForErrors()
	{
		$errors = array(
			'required' => 'Поле :attribute должно быть указано.',
			'numeric'  => 'Сумма указана не корректно.',
		);

		return $errors;
	}

	public static function rulesForNewBill()
	{
		$rules = array(
			'user'       => 'required|regex:/^tel:\+\d{1,15}$/',
			'amount'     => 'required|numeric|regex:/^\d+(.\d{0,3})?$/',
			'ccy'        => 'required|regex:/^[a-zA-Z]{3}$/',
			'comment'    => 'regex:/^\.{0,255}$/',
			'lifetime'   => 'regex:/^\d{4}-\d{2}-\d{2}T \d{2}:\d{2}:\d{2}$/',
			'pay_source' => 'regex:/^((mobile)|(qw)){1}$/',
			'prv_name'   => 'regex:/^\.{1,100}$/',
		);

		return $rules;
	}


}