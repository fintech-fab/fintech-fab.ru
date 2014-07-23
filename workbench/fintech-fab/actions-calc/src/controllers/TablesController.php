<?php

namespace FintechFab\ActionsCalc\Controllers;


use FintechFab\ActionsCalc\Components\AuthCheck;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Signal;
use FintechFab\ActionsCalc\Models\Rule;
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

		//проверяем данные
		$errors = Validators::getErrorFromChangeDataEventsTable($input);
		if ($errors) {
			return $errors;
		}

		//Изменяем данные
		$eventData = Event::find($input['id']);
		$message = 'Данные изменены';
		$eventData->changeEvent($input);

		return array('message' => $message);

	}

	public function postAddDataEvents()
	{
		$input = Input::only('name', 'event_sid');
		$input['name'] = e($input['name']);
		//проверяем данные
		$errors = Validators::getErrorFromChangeDataEventsTable($input);
		if ($errors) {
			return $errors;
		}

		$terminal = AuthCheck::getTerm();
		$input['terminal_id'] = $terminal['id'];

		$event = New Event();
		$event->newEvent($input);
		$message = 'Данные изменены';

		return array('message' => $message);

	}

	public function postChangeDataSignals()
	{
		$input = Input::only('id', 'name', 'signal_sid');

		//проверяем данные
		$errors = Validators::getErrorFromChangeDataSignalsTable($input);
		if ($errors) {
			return $errors;
		}

		//Изменяем данные
		$signalData = Signal::find($input['id']);
		$message = 'Данные изменены';
		$signalData->changeSignal($input);

		return array('message' => $message);

	}

	public function postAddDataSignals()
	{
		$input = Input::only('name', 'signal_sid');
		$input['name'] = e($input['name']);
		//проверяем данные
		$errors = Validators::getErrorFromChangeDataSignalsTable($input);
		if ($errors) {
			return $errors;
		}

		$terminal = AuthCheck::getTerm();
		$input['terminal_id'] = $terminal['id'];

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

		$res = "Изменения произошли для правила с порядковым номером  $id";

		return $res;

	}

	public function postChangeDataRule()
	{
		$input = Input::all();
		$input['name'] = e($input['name']);
		$id = $input['id'];

		$errors = Validators::getErrorFromChangeDataRuleTable($input);
		if ($errors) {
			return $errors;
		}

		//Изменяем данные
		$rule = Rule::find($id);
		$message = 'Данные изменены';
		$rule->changeRule($input);

		return array('message' => $message);

	}

	public function postAddDataRule()
	{
		$input = Input::all();

		$errors = Validators::getErrorFromChangeDataRuleTable($input);
		if ($errors) {
			return $errors;
		}
		$terminal = AuthCheck::getTerm();
		$input['terminal_id'] = $terminal['id'];

		//Изменяем данные
		$message = 'Данные изменены';
		$rule = New Rule();
		$rule->newRule($input);

		return array('message' => $message);

	}
}