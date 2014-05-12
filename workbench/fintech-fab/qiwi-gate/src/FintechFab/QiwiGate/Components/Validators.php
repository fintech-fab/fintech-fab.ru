<?php
namespace FintechFab\QiwiGate\Components;


class Validators
{

	public static function rulesForNewBill()
	{
		$rules = array(
			'user'       => 'required|regex:/^tel:\+\d{1,15}$/',
			'amount'     => 'required|numeric|regex:/^\d+(.\d{0,2})?$/',
			'ccy'        => 'required|regex:/^[a-zA-Z]{3}$/',
			'comment'    => 'regex:/^.{0,255}$/',
			'lifetime'   => 'regex:/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/',
			'pay_source' => 'in:mobile,qw',
			'prv_name'   => 'regex:/^.{1,100}$/',
		);

		return $rules;
	}

	public static function rulesForRefundBill()
	{
		$rules = array(
			'amount' => 'required|numeric|regex:/^\d+(.\d{0,2})?$/',
		);

		return $rules;
	}

	public static function rulesForAccount()
	{
		$rules = array(
			'username'        => 'required|alpha_dash',
			'callback'        => 'url',
			'password'        => 'required|min:4|alpha_dash',
			'confirmPassword' => 'required|same:password',
		);

		return $rules;
	}

	public static function messagesForErrors()
	{
		$rules = array(
			'required'   => 'Поле должно быть заполнено.',
			'regex'      => 'Некорректный формат данных.',
			'url'        => 'Некорректный адрес',
			'min'        => 'Должен быть длиннее :min символов.',
			'alpha_dash' => 'Только буквы, цифры, тире и подчёткивания ',
			'same'       => 'Пароли не одинаковы',
		);

		return $rules;
	}


}