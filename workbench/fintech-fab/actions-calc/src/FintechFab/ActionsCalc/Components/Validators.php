<?php
namespace FintechFab\ActionsCalc\Components;


use App;
use Config;
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

	public static function messagesForErrorsRules()
	{
		$rules = array(
			'exists' => 'Для этого события нету правил',
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
	public static function ValidateInput($input)
	{
		$sidTermValidator = Validator::make($input, Validators::rulesForRequest());

		if ($sidTermValidator->fails()) {
			$aFailMessages = $sidTermValidator->failed();
			Log::info('Ошибки валидации: ', $aFailMessages);
			App::abort(500);
			exit();
		}
	}


	public static function rulesForTableDataEventsUnique()
	{
		$rules = array(
			'name'      => 'required',
			'event_sid' => 'required|alpha_dash|unique:' . Config::get('database.connections.ff-actions-calc.database') . '.events',
		);

		return $rules;
	}

	public static function rulesForTableDataEvents()
	{
		$rules = array(
			'name'      => 'required',
			'event_sid' => 'required|alpha_dash',
		);

		return $rules;
	}

	public static function rulesForTableDataSignalsUnique()
	{
		$rules = array(
			'name'       => 'required',
			'signal_sid' => 'required|alpha_dash|unique:' . Config::get('database.connections.ff-actions-calc.database') . '.signals',
		);

		return $rules;
	}

	public static function rulesForTableDataSignals()
	{
		$rules = array(
			'name'       => 'required',
			'signal_sid' => 'required|alpha_dash',
		);

		return $rules;
	}


	public static function rulesForTableDataRule()
	{
		$rules = array(
			'name'       => 'required',
			'rule'       => 'required',
			'signal_id'  => 'required|alpha_dash',
			'signal_sid' => 'required|alpha_dash|exists:' . Config::get('database.connections.ff-actions-calc.database') . '.signals',
			'event_id'   => 'required|alpha_dash',
			'event_sid'  => 'required|alpha_dash|exists:' . Config::get('database.connections.ff-actions-calc.database') . '.events',
		);

		return $rules;
	}

	public static function rulesForTablerulesWithEvent()
	{
		$rules = array(
			'event_id' => 'required|alpha_dash|exists:' . Config::get('database.connections.ff-actions-calc.database') . '.rules,event_id',
		);

		return $rules;
	}

	public static function rulesForTableAddDataRule()
	{
		$rules = array(
			'name'     => 'required',
			'rule'     => 'required',
			'event_id' => 'required|alpha_dash',
		);

		return $rules;
	}

	public static function rulesForTableAddDataRuleSignals()
	{
		$rules = array(
			'signal_id' => 'required|alpha_dash',
		);

		return $rules;
	}


	public static function getErrorFromChangeDataEventsTableUnique($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableDataEventsUnique(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'      => $userMessages->first('name'),
				'event_sid' => $userMessages->first('event_sid'),
			);

			return $result;
		}

		return null;

	}

	public static function getErrorFromChangeDataEventsTable($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableDataEvents(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'name'      => $userMessages->first('name'),
				'event_sid' => $userMessages->first('event_sid'),
			);

			return $result;
		}

		return null;

	}

	public static function getErrorFromChangeDataSignalsTableUnique($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableDataSignalsUnique(), Validators::messagesForErrors());
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

	public static function getErrorFromChangeDataSignalsTable($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableDataSignals(), Validators::messagesForErrors());
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

	public static function getErrorFromAddDataRuleTable($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableAddDataRule(), Validators::messagesForErrors());
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

	public static function getErrorFromAddDataRuleTableSignals($data)
	{

		$val['signal_id'] = $data;
		json_encode($val);
		$validator = Validator::make($data, Validators::rulesForTableAddDataRuleSignals(), Validators::messagesForErrors());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result = $userMessages->first('signal_id');

			return $result;
		}

		return null;

	}

	public static function getErrorFromChangeDataRuleTable($data)
	{
		$data['name'] = e($data['name']);
		$validator = Validator::make($data, Validators::rulesForTableAddDataRule(), Validators::messagesForErrors());
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


	public static function rulesWithEvent($data)
	{
		$validator = Validator::make($data, Validators::rulesForTablerulesWithEvent(), Validators::messagesForErrorsRules());
		$userMessages = $validator->messages();

		if ($validator->fails()) {
			$result['errors'] = array(
				'event_id' => $userMessages->first('event_id'),
			);

			return $result;
		}

		return null;

	}


}