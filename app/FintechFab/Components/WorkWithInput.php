<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 14:42
 */

namespace FintechFab\Components;


class WorkWithInput
{

	public static function messagesForErrors()
	{
		$rules = array(
			'required'   => 'Поле :attribute должно быть заполнено.',
			'min'        => 'Поле :attribute должно содержать не менее :min символов.',
			'unique'     => 'Пользователь с таким E-mail уже зарегистрирован.',
			'email'      => 'Адрес E-mail должен быть корректным',
			'same'       => 'Пароли должны совпадать.',
			'numeric'    => 'Введите корректную сумму.',
			'size'       => 'Укажите номер телефона.',
			'alpha_dash' => 'Поле :attribute должно содержать только латинские символы, цифры, знаки подчёркивания и дефисы.',
		);

		return $rules;
	}

	public static function rulesForInputRegistration()
	{
		$rules = array(
			'first_name'     => 'required',
			'email'          => 'required|email|unique:users',
			'password'       => 'required|min:4|alpha_dash',
			'passwordRepeat' => 'same:password',
		);

		return $rules;
	}

	public static function rulesForInputAuth()
	{
		$rules = array(
			'email'    => 'required|email',
			'password' => 'required|alpha_dash',
		);

		return $rules;
	}

	public static function rulesForInputBuy()
	{
		$rules = array(
			'sum' => 'required|numeric',
			'tel' => 'required|size:12', //не смог вставить регулярное выражение regex:tel:\+\d{1,15}
		);

		return $rules;
	}

} 