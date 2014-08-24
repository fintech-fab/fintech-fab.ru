<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Components\Validators;
use FintechFab\ActionsCalc\Models\Event;
use Validator;
use Input;
use View;

class EventController extends BaseController
{

	public function create()
	{
		$oRequestData = Input::all();
		$oRequestData['terminal_id'] = $this->iTerminalId;

		$validator = Validator::make($oRequestData, Validators::getEventRules());

		if ($validator->fails()) {
			return ['status' => 'error', 'errors' => $validator->errors()];
		} else {
			$oEvent = Event::create($oRequestData);
			$oEvent->push();

			return json_encode(['status' => 'success', 'message' => 'Новое событие создано.']);
		}
	}

	public function updateEventTable()
	{
		$aoEvents = Event::where('terminal_id', '=', $this->iTerminalId)->orderBy('created_at', 'desc')->paginate(10);
		$aoEvents->setBaseUrl('events/table');

		return View::make('ff-actions-calc::calculator._events_table', [
			'events' => $aoEvents
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
