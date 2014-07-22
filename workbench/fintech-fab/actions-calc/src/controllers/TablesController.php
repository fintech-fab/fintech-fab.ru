<?php

namespace FintechFab\ActionsCalc\Controllers;


use FintechFab\ActionsCalc\Components\AuthCheck;
use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Event;
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

		$Signals = $terminal->Signal()->paginate(10);

		return $this->make('tableSignals', array(
			'signals' => $Signals,
		));
	}

	public function postGetData($action)
	{
		$id = Input::get('id');
		$data = Event::find($id);

		return $data;


	}

	public function postChangeData()
	{

		$input = Input::only('id', 'name', 'event_sid');

		//проверяем данные
		$errors = Validators::getErrorFromChangeDataTable($input);
		if ($errors) {
			return $errors;
		}


		//Изменяем данные
		$eventData = Event::find($input['id']);
		$message = 'Данные изменены';
		$eventData->changeEvent($input);

		return array('message' => $message);

	}

	public function postAddData()
	{
		$input = Input::only('name', 'event_sid');

		//проверяем данные
		$errors = Validators::getErrorFromChangeDataTable($input);
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
}