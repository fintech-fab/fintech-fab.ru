<?php

namespace FintechFab\ActionsCalc\Controllers;

use FintechFab\ActionsCalc\Models\Event;
use Request;
use Input;

class EventController extends BaseController
{

	public function create()
	{
		$oRequestData = Input::all();
		$oRequestData['terminal_id'] = $this->iTerminalId;

		$oEvent = Event::create($oRequestData);
		$oEvent->push();

		return json_encode(['status' => 'success', 'message' => 'Новое событие создано.']);
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
