<?php


namespace FintechFab\ActionsCalc\Components\Validators;

use Config;
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


	public static function rulesForAdd($data)
	{
		$database = Config::get('database.connections.ff-actions-calc.database');
		$rules = array(
			'name'     => 'required',
			'rule'     => 'required',
			'event_id' => 'required|alpha_dash|exists:' . $database . '.events,id,terminal_id,' . $data['terminal_id'],
		);

		return $rules;
	}

	public static function rulesForChange($data)
	{
		$database = Config::get('database.connections.ff-actions-calc.database');
		$rules = array(
			'id'        => 'required|integer',
			'name'      => 'required',
			'rule'      => 'required',
			'event_id'  => 'required|alpha_dash|exists:' . $database . '.events,id,terminal_id,' . $data['terminal_id'],
			'signal_id' => 'required|alpha_dash|exists:' . $database . '.signals,id,terminal_id,' . $data['terminal_id'],
		);

		return $rules;
	}


	public static function rulesForCheckSignals($data)
	{
		$database = Config::get('database.connections.ff-actions-calc.database');
		$rules = array(
			'signal_id' => 'required|alpha_dash|exists:' . $database . '.signals,id,terminal_id,' . $data['terminal_id'],
		);

		return $rules;
	}


	public static function add($data)
	{
		$data['name'] = e($data['name']);

		$validator = Validator::make($data, self::rulesForAdd($data), self::messagesForErrors());
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
		$validator = Validator::make($data, self::rulesForCheckSignals($data), self::messagesForErrors());
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
		$validator = Validator::make($data, self::rulesForChange($data), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'id'        => $userMessages->first('id'),
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