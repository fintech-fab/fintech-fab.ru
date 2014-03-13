<?php

namespace FintechFab\Components;


class Helper
{
	public static function ucwords($str)
	{
		$str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");

		return ($str);
	}


} 