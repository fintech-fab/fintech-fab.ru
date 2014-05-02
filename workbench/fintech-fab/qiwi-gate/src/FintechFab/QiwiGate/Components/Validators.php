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
			'pay_source' => 'regex:/^((mobile)|(qw)){1}$/',
			'prv_name'   => 'regex:/^.{1,100}$/',
		);

		return $rules;
	}

	public static function rulesForRefundBill()
	{
		$rules = array(
			'amount' => 'required|numeric|min:10|max:5000|regex:/^\d+(.\d{0,2})?$/',
		);

		return $rules;
	}

	public static function rulesForPayment()
	{
		$rules = array(
			'user' => 'required|regex:/^\+\d{1,15}$/',
		);

		return $rules;
	}

	public static function messagesPaymentErrors()
	{
		$rules = array(
			'required' => 'Поле должно быть заполнено.',
			'regex'    => 'Некорректный формат телефона',
		);

		return $rules;
	}

}