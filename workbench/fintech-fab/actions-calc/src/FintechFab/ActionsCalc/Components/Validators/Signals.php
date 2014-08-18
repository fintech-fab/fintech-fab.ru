<?php


namespace FintechFab\ActionsCalc\Components\Validators;

use Config;
use Validator;

class Signals
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

	public static function rulesForChange($data)
	{
		$rules = array(
			'name'       => 'required',
			'signal_sid' => 'required|alpha_dash|unique:' . Config::get('database.connections.ff-actions-calc.database') . '.signals,signal_sid,' . $data['id'] . ',id,terminal_id,' . $data['terminal_id'],
		);

		return $rules;
	}

	public static function change($data)
	{
		$data['name'] = e($data['name']);
		$dataForChange['id'] = $data['id'];
		$dataForChange['terminal_id'] = $data['terminal_id'];
		$validator = Validator::make($data, self::rulesForChange($dataForChange), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'       => $userMessages->first('name'),
				'signal_sid' => $userMessages->first('signal_sid'),
			);

			return $result;
		}

		return null;

	}


	public static function add($data)
	{
		$data['name'] = e($data['name']);

		$dataForChange['id'] = null;
		$dataForChange['terminal_id'] = $data['terminal_id'];
		$validator = Validator::make($data, self::rulesForChange($dataForChange), self::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'       => $userMessages->first('name'),
				'signal_sid' => $userMessages->first('signal_sid'),
			);

			return $result;
		}

		return null;

	}


} 