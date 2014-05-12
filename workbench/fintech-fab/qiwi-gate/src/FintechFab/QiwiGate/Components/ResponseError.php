<?php

namespace FintechFab\QiwiGate\Components;


class ResponseError
{


	const DATA = 5;
	const AUTH = 150;

	public static $errors = array(
		self::DATA => 400,
		self::AUTH => 401,
	);


} 