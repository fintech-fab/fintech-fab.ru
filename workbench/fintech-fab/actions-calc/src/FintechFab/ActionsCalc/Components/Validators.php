<?php
namespace FintechFab\ActionsCalc\Components;


use App;
use Log;
use Validator;

class Validators
{

	public static function rulesForRegisterAcc()
	{
		$rules = array(
			'termId'          => 'required|integer',
			'username'        => 'required',
			'url'             => 'url',
			'queue'           => '',
			'key'             => '',
			'password'        => 'required|min:4|alpha_dash',
			'confirmPassword' => 'required|same:password',
		);

		return $rules;
	}

	public static function rulesForChangeAccData()
	{
		$rules = array(
			'username'        => 'required',
			'url'             => 'url',
			'queue'           => '',
			'key'             => '',
			'password'        => 'min:4|alpha_dash',
			'confirmPassword' => 'required_with:password|same:password',
		);

		return $rules;
	}

	public static function rulesForRequest()
	{
		$rules = array(
			'term'  => 'required|integer',
			'event' => 'required|alpha_dash',
		);

		return $rules;
	}

	public static function messagesForErrors()
	{
		$rules = array(
			'required'      => 'Поле должно быть заполнено.',
			'regex'         => 'Некорректный формат данных.',
			'url'           => 'Некорректный адрес',
			'min'           => 'Должен быть длиннее :min символов.',
			'alpha_dash'    => 'Только буквы, цифры, тире и подчёткивания.',
			'same'          => 'Пароли не одинаковы',
			'required_with' => 'Подтвердите пароль',
			'unique'        => 'Такой sid уже существует',
			'exists'        => 'Такой sid не существует',
		);

		return $rules;
	}

	public static function getErrorFromRegData($data)
	{
		$data['username'] = e($data['username']);
		$validator = Validator::make($data, Validators::rulesForRegisterAcc(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'termId'          => $userMessages->first('termId'),
				'username'        => $userMessages->first('username'),
				'url'             => $userMessages->first('url'),
				'queue'           => $userMessages->first('queue'),
				'key'             => $userMessages->first('key'),
				'password'        => $userMessages->first('password'),
				'confirmPassword' => $userMessages->first('confirmPassword'),
			);

			return $result;
		}

		return null;
	}

	public static function getErrorFromChangeData($data)
	{
		$data['username'] = e($data['username']);
		$validator = Validator::make($data, Validators::rulesForChangeAccData(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'username'        => $userMessages->first('username'),
				'url'             => $userMessages->first('url'),
				'queue'           => $userMessages->first('queue'),
				'key'             => $userMessages->first('key'),
				'password'        => $userMessages->first('password'),
				'confirmPassword' => $userMessages->first('confirmPassword'),
			);

			return $result;
		}

		return null;
	}

	/**
	 * @param $input
	 */
	public static function ValidateRequest($input)
	{

		$sidTermValidator = Validator::make($input, Validators::rulesForRequest());

		if ($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			Log::info('Ошибки валидации: ', $aFailMessages);
			App::abort(500);
			exit();
		}
	}


}