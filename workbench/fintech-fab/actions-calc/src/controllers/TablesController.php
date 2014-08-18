<?php

namespace FintechFab\ActionsCalc\Controllers;


use FintechFab\ActionsCalc\Components\AuthCheck;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Components\Validators\Events;
use FintechFab\ActionsCalc\Components\Validators\Rules;
use FintechFab\ActionsCalc\Components\Validators\Signals;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use Input;

class TablesController extends BaseController
{

	public $layout = 'tables';

	/**Таблица правил
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableRules()
	{
		$terminal = AuthCheck::getTerm();

		$rules = $terminal->rules()->paginate(10);

		return $this->make('tableRules', array(
			'rules' => $rules,
		));
	}

	/**Таблица событий
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableEvents()
	{
		$terminal = AuthCheck::getTerm();

		$events = $terminal->event()->paginate(10);

		return $this->make('tableEvents', array(
			'events' => $events,
		));
	}


	public function postViewRule()
	{
		$input = Input::only('event_id');
		$event = event::find($input['event_id']);

		$errors = Events::EventWithRules($input);
		if ($errors) {
			return $errors;
		}

		$rules = $event->rules()->paginate(10);


		return $this->make('tableRulesForEvents', array(
			'rules' => $rules,
		));
	}

	/**Таблица сигналов
	 *
	 * @return \Illuminate\View\View
	 */
	public function tableSignals()
	{
		$terminal = AuthCheck::getTerm();

		$signals = $terminal->Signal()->paginate(10);

		return $this->make('tableSignals', array(
			'signals' => $signals,
		));
	}

	public function postChangeDataEvents()
	{
		$input = Input::only('id', 'name', 'event_sid');
		$input['terminal_id'] = $this->getTerminalId();
		//проверяем данные

		$errors = Events::change($input);
		if ($errors) {
			return $errors;
		}

		//Изменяем данные
		$event = Event::whereId($input['id'])->whereTerminalId($input['terminal_id'])->first();

		if (!$event) {
			return array('message' => 'Событие не найдено');
		}
		$message = 'Данные изменены';
		$event->changeEvent($input);

		return array('message' => $message);

	}

	public function postAddDataEvents()
	{
		$input = Input::only('name', 'event_sid');
		$input['name'] = e($input['name']);
		$input['terminal_id'] = $this->getTerminalId();
		//проверяем данные
		$errors = Events::add($input);
		if ($errors) {
			return $errors;
		}

		$event = New Event();
		$event->newEvent($input);
		$message = 'Данные изменены';

		return array('message' => $message);

	}

	public function postChangeDataSignals()
	{
		$input = Input::only('id', 'name', 'signal_sid');
		$input['terminal_id'] = $this->getTerminalId();
		//проверяем данные

		$errors = Signals::change($input);
		if ($errors) {
			return $errors;
		}


		//Изменяем данные
		$signal = Signal::whereId($input['id'])->whereTerminalId($input['terminal_id'])->first();

		if (!$signal) {
			return array('message' => 'Сигнал не найден');
		}
		$message = 'Данные изменены';
		$signal->changeSignal($input);

		return array('message' => $message);

	}

	public function postAddDataSignals()
	{
		$input = Input::only('name', 'signal_sid');
		$input['name'] = e($input['name']);
		$input['terminal_id'] = $this->getTerminalId();
		//проверяем данные
		$errors = Signals::add($input);
		if ($errors) {
			return $errors;
		}

		$signal = New Signal();
		$signal->newSignal($input);
		$message = 'Данные изменены';

		return array('message' => $message);

	}

	public function postChangeFlagRule()
	{
		$input = Input::all();
		$val = $input['val'];
		$id = $input['id'];

		$rule = Rule::find($id);

		if ($val == "true") {
			$rule->changeFlag(1);
		} else {
			$rule->changeFlag(0);
		}

		return $id;

	}

	public function postChangeDataRule()
	{
		$input = Input::all();
		$input['name'] = e($input['name']);
		$input['terminal_id'] = $this->getTerminalId();
		$errors = Rules::change($input);
		if ($errors) {
			if ($errors['errors']['id'] != '') {
				return array('message' => 'Какая-то ошибка, повторите попытку');
			}

			return $errors;
		}

		//Изменяем данные
		$id = $input['id'];

		$rule = Rule::find($id);
		$message = 'Данные изменены';
		$rule->changeRule($input);

		return array('message' => $message);
	}

	public function postAddDataRule()
	{
		$signal_id = Input::only('signal_id');
		$sid = $signal_id['signal_id'];

		$input = Input::only('event_id', 'rule', 'name');
		$input['terminal_id'] = $this->getTerminalId();
		$errors = Rules::add($input);
		$data['terminal_id'] = $input['terminal_id'];
		foreach ($sid as $key => $value) {
			$data['signal_id'] = $value;
			$error = Rules::checkSignals($data);
			if ($error != null) {
				$errors['errors']['signal_id'][$key] = $error;
			}
		}

		if ($errors) {
			return $errors;
		}

		//Изменяем данные
		$message = 'Данные изменены';

		foreach ($sid as $value) {
			$input['signal_id'] = $value;
			$rule = New Rule();
			$rule->name = $input['name'];
			$rule->rule = $input['rule'];
			$rule->event_id = $input['event_id'];
			$rule->signal_id = $input['signal_id'];
			$rule->terminal_id = $input['terminal_id'];
			$rule->flag_active = 1;
			$rule->save();
		}

		return array('message' => $message);

	}

	private function getTerminalId()
	{
		$terminal = AuthCheck::getTerm();

		return $terminal->id;
	}
}