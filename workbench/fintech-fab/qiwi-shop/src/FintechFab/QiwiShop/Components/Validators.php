<?php
namespace FintechFab\QiwiShop\Components;


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
		$rules = array(
			'item'    => 'required|regex:/^.{0,255}$/',
			'sum'     => 'required|numeric|regex:/^\d+(.\d{0,2})?$/',
			'tel'     => 'required|regex:/^\+\d{1,15}$/',
			'comment' => 'regex:/^.{0,255}$/',
		);

		return $rules;
	}

	public static function rulesForPayReturn()
	{
		$rules = array(
			'sum'     => 'required|numeric|regex:/^\d+(.\d{0,2})?$/',
			'comment' => 'regex:/^.{0,255}$/',
		);

		return $rules;
	}

	public static function messagesForErrors()
	{
		$rules = array(
			'required' => 'Поле :attribute должно быть заполнено.',
			'regex'    => 'Неправильный формат данных.',
			'numeric'  => 'Введите корректную сумму.',
		);

		return $rules;
	}

}