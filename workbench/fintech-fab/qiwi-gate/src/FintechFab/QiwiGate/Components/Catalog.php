<?php
namespace FintechFab\QiwiGate\Components;


class Catalog
{

	const C_WITHOUT_ERRORS = 0;
	const C_WRONG_FORMAT = 5;
	const C_WRONG_AUTH = 150;
	const C_BILL_NOT_FOUND = 210;
	const C_BILL_ALREADY_EXIST = 215;
	const C_SMALL_AMOUNT = 241;
	const C_BIG_AMOUNT = 242;
	const C_TECHNICAL_ERROR = 300;

	public static function serverCode($error)
	{
		$serverCode = array(
			self::C_WITHOUT_ERRORS     => 200,
			self::C_WRONG_FORMAT       => 400,
			self::C_WRONG_AUTH         => 401,
			self::C_SMALL_AMOUNT       => 403,
			self::C_BIG_AMOUNT         => 403,
			self::C_BILL_ALREADY_EXIST => 403,
			self::C_BILL_NOT_FOUND     => 404,
			self::C_TECHNICAL_ERROR    => 500,
		);

		return $serverCode[$error];
	}

} 