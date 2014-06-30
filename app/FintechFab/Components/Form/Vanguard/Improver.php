<?php

namespace FintechFab\Components\Form\Vanguard;


class Improver
{

	public static $directions = array(
		'php'     => 'php',
		'css'     => 'Верстка',
		'android' => 'Android',
		'ios'     => 'IoS',
		'other'   => 'other',
	);


	public static function getDirectionName($directionKey)
	{
		return isset(self::$directions[$directionKey])
			? self::$directions[$directionKey]
			: null;

	}

	public static function getDirectionList()
	{
		return self::$directions;
	}

} 