<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use Input;

class RequestController extends Controller {

	public function getRequest(){
		$input = Input::all();
		dd($input);
	}

} 