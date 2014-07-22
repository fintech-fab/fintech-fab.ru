<?php
/**
 * Class RequestController
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

use Illuminate\Routing\Controller;

class RequestController extends Controller
{
	public function getRequest() {
		$data = Input::get('all');
		return $data;
	}
}

 