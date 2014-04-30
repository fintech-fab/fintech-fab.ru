<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use Input;

class OrderController extends Controller
{

	public function index()
	{
		$data = Input::all();
		dd($data);
	}

}