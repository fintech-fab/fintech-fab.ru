<?php

namespace FintechFab\ActionsCalc\Controllers;

use Controller;
use View;

/**
 * Class CalculatorController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class CalculatorController extends Controller {
	
	public function manage() {
	    return View::make('ff-actions-calc::layout.main');
	}
}