<?php
namespace FintechFab\QiwiShop\Components\Sdk;

use App;

/**
 * Class CurlFactory
 *
 * @return Curl $curl
 */
class CurlFactory
{
	public static function create()
	{
		$curl = new Curl;
		if (App::environment() == 'testing') {
			$curl = new CurlTest();
		}

		return $curl;
	}

} 