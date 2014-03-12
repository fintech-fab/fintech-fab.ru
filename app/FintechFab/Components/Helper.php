<?php

namespace FintechFab\Components;


use Auth;
use FintechFab\Models\User;

class Helper
{
	public static function ucwords($str)
	{
		$str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");

		return ($str);
	}

	public static function messagesForErrors()
	{
		$rules = array(
			'required' => 'Поле :attribute должно быть заполнено.',
			'min'        => 'Поле :attribute должно содержать не менее :min символов.',
			'unique'     => 'Пользователь с таким E-mail уже зарегистрирован.',
			'email'      => 'Адрес E-mail должен быть корректным',
			'same'       => 'Пароли должны совпадать.',
			'alpha_dash' => 'Поле :attribute должно содержать только латинские символы, цифры, знаки подчёркивания и дефисы.',
		);

		return $rules;
	}

	public static function rulesForInput()
	{
		$rules = array(
			'first_name'     => 'required',
			'email'          => 'required|email|unique:users',
			'password'       => 'required|min:4|alpha_dash',
			'passwordRepeat' => 'same:password',
		);

		return $rules;
	}

	public static function linkForUserProfile()
	{
		$first_name = Helper::user()->first_name;
		$last_name = Helper::user()->last_name;
		$link_user = '<li><a href="">' . $first_name . ' ' . $last_name . '</a></li>';

		$link_logout = '<li><a href="/logout">Выход</a></li>';

		return $link_user . $link_logout;
	}

	/**
	 * @return \Illuminate\Auth\UserInterface|null|User
	 */
	public static function user()
	{
		return Auth::user();
	}
} 