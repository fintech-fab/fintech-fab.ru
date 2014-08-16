<?php


namespace FintechFab\ActionsCalc\Components\Validators;

use App;
use Validator;

class Rules
{

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


	public static function rulesForAdd()
	{
		$rules = array(
			'name'     => 'required',
			'rule'     => 'required',
			'event_id' => 'required|alpha_dash',
		);

		return $rules;
	}

	public static function rulesForChange()
	{
		$rules = array(
			'name'      => 'required',
			'rule'      => 'required',
			'event_id'  => 'required|alpha_dash',
			'signal_id' => 'required|alpha_dash',
		);

		return $rules;
	}


	public static function rulesForCheckSignals()
	{
		$rules = array(
			'signal_id' => 'required|alpha_dash',
		);

		return $rules;
	}


	public static function add($data)
	{
		$data['name'] = e($data['name']);

		$validator = Validator::make($data, self::rulesForAdd(), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'     => $userMessages->first('name'),
				'rule'     => $userMessages->first('rule'),
				'event_id' => $userMessages->first('event_id'),
			);

			return $result;
		}

		return null;

	}

	public static function checkSignals($data)
	{

		$val['signal_id'] = $data;
		json_encode($val);
		$validator = Validator::make($data, self::rulesForCheckSignals(), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result = $userMessages->first('signal_id');

			return $result;
		}

		return null;

	}


	public static function change($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, self::rulesForChange(), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'      => $userMessages->first('name'),
				'rule'      => $userMessages->first('rule'),
				'signal_id' => $userMessages->first('signal_id'),
				'event_id'  => $userMessages->first('event_id'),
			);

			return $result;
		}

		return null;

	}


} 