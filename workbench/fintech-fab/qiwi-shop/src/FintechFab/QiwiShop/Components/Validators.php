<?php
namespace FintechFab\QiwiShop\Components;


use Config;

class Validators
{

	public static function rulesForNewBill()
	{
		$rules = array(
			'user'       => 'required|regex:/^tel:\+\d{1,15}$/',
			'amount'     => 'required|numeric|regex:/^\d+(.\d{0,2})?$/',
			'ccy'        => 'required|regex:/^[a-zA-Z]{3}$/',
			'comment'    => 'regex:/^.{0,255}$/',
			'lifetime'   => 'regex:/^\d{4}-\d{2}-\d{2}T \d{2}:\d{2}:\d{2}$/',
			'pay_source' => 'regex:/^((mobile)|(qw)){1}$/',
			'prv_name'   => 'regex:/^.{1,100}$/',
		);

		return $rules;
	}

	public static function rulesForNewOrder()
	{
		$min = Config::get('ff-qiwi-shop::minSum');
		$max = Config::get('ff-qiwi-shop::maxSum');
		$rules = array(
			'item'    => 'required|alpha_dash|regex:/^.{0,255}$/',
			'sum'     => 'required|numeric|regex:/^\d+(.\d{0,2})?$/ |min:' . $min . '|max:' . $max,
			'tel'     => 'required|regex:/^\+\d{1,15}$/',
			'comment' => 'alpha_dash|regex:/^.{0,255}$/',
		);

		return $rules;
	}

	public static function messagesForErrors()
	{
		$min = Config::get('ff-qiwi-shop::minSum');
		$max = Config::get('ff-qiwi-shop::maxSum');
		$rules = array(
			'required'   => 'Поле :attribute должно быть заполнено.',
			'regex'      => 'Неправильный формат данных.',
			'min'        => "Сумма должна быть больше $min руб.",
			'max'        => "Сумма должна быть меньше $max руб.",
			'numeric'    => 'Введите корректную сумму.',
			'alpha_dash' => 'Поле должно содержать только символы, цифры, знаки подчёркивания и дефисы.',
		);

		return $rules;
	}

}