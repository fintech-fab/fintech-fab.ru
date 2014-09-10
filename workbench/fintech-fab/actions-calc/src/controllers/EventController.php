<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Event;
use Paginator;
use Validator;
use Input;
use View;
use Request;

/**
 * Class EventController
 *
 * @package FintechFab\ActionsCalc\Controllers
 */
class EventController extends BaseController
{

	/**
	 * @var bool
	 */
	public $restful = true;

	/**
	 * Create event
	 *
	 * @return array|string
	 */
	public function create()
	{
		$oRequestData = Input::all();
		$oRequestData['terminal_id'] = $this->iTerminalId;

		$validator = Validator::make($oRequestData, Validators::getEventRules());

		if ($validator->fails()) {
			return json_encode(['status' => 'error', 'errors' => $validator->errors()]);
		}

		$oEvent = Event::create($oRequestData);
		$oEvent->push();

		return json_encode(['status' => 'success', 'message' => 'Новое событие создано.']);
	}

	/**
	 * Update event
	 *
	 * @param $id
	 *
	 * @return \Illuminate\View\View|string
	 */
	public function update($id)
	{
		/** @var Event $event */
		$event = Event::find($id);

		// only view on GET
		if (Request::isMethod('GET')) {
			return View::make('ff-actions-calc::event.update', ['event' => $event]);
		}

		// update process
		$oRequestData = Input::only('id', 'event_sid', 'name');

		$aValidators = Validators::getEventRules();
		// ignoring uniquiness of event_sid on update
		$aValidators['event_sid'] = $aValidators['event_sid'] . ',' . $id;
		$validator = Validator::make($oRequestData, $aValidators);

		if ($validator->fails()) {
			return json_encode(['status' => 'error', 'errors' => $validator->errors()]);
		}

		$event->name = $oRequestData['name'];
		$event->event_sid = $oRequestData['event_sid'];

		if ($event->save()) {
			return json_encode(['status' => 'success', 'message' => 'Событие обновлено.', 'update' => $oRequestData]);
		}

		return json_encode(['status' => 'error', 'message' => 'Не удалось обновить событие.']);
	}

	/**
	 * Delete event
	 *
	 * @return string
	 */
	public function delete()
	{
		$aRequest = Input::only('id');

		/** @var Event $event */
		$event = Event::find((int)$aRequest['id']);

		if ($event->rules->count() > 0) {
			return ['status' => 'error', 'message' => 'Сначала удалите правила.'];
		}

		if ($event->delete()) {
			return json_encode(['status' => 'success', 'message' => 'Событие удалено.']);
		}

		return json_encode(['status' => 'error', 'message' => 'Не удалось удалить событие.']);
	}

	/**
	 * Update events table
	 *
	 * @return \Illuminate\View\View|string
	 */
	public function updateEventsTable()
	{
		$input = Input::all();
		$iPage = (int)$input['page'];

		// setting page that stored in span#pagination-events-current-page in _events.php
		Paginator::setCurrentPage($iPage);

		$aoEvents = Event::where('terminal_id', '=', $this->iTerminalId)->orderBy('created_at', 'desc')->paginate(10);
		$aoEvents->setBaseUrl('/actions-calc/events/table');

		return View::make('ff-actions-calc::calculator._events_table', [
			'events' => $aoEvents
		]);
	}

	/**
	 * Event search
	 *
	 * @return \Illuminate\View\View
	 */
	public function search()
	{
		$q = e(Input::get('q'));
		$aoEvents = Event::where('event_sid', 'LIKE', "%$q%")->get();

		return View::make('ff-actions-calc::calculator._events_table', [
			'events' => $aoEvents,
		]);
	}

}
