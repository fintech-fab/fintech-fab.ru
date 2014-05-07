<?php
namespace FintechFab\QiwiGate\Widgets;

use URL;

class NavbarAction
{

	/**
	 * @param $url
	 *
	 * @return string
	 */
	public static function isActive($url)
	{
		if (URL::current() == $url) {
			return 'active';
		}

		return '';
	}


} 