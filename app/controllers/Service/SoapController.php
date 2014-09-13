<?php
/**
 * Created by PhpStorm.
 * User: m.novikov
 * Date: 07.04.14
 * Time: 11:49
 */

namespace App\Controllers\Service;


use Controller;
use FintechFab\Components\Soap\VanguardOrder;
use SoapServer;

class SoapController extends Controller
{

	public function index()
	{
		$oServer = new SoapServer (
			null,
			array(
				'uri'    => "http://fintech-fab.dev",
				'asd'    => 123213,
				'asdasd' => 'asdasdsa'
			)
		);
		$oServer->setClass(VanguardOrder::class);
	}

} 