<?php

use FintechFab\ActionsCalc\Models\Terminal;
use FintechFab\ActionsCalc\Models\Rule;

class TestSetUp extends TestCase
{
	protected $aRequestData;

	public function setUp()
	{
		$sSignature = sha1("1|under_rain|key");
		$this->aRequestData = [
			'terminal_id' => 1,
			'event_sid'   => 'under_rain',
			'data'        => json_encode(['test' => 1]),
			'auth_sign'   => $sSignature
		];

		parent::setUp();

		// Clearing tables on every test
		Terminal::truncate();
		Rule::truncate();

		// Fill in tables on every test
		// Rules
		Rule::create([
			'id'          => 1,
			'name'        => 'Rule1',
			'terminal_id' => 1
		]);
		Rule::create([
			'id'          => 2,
			'name'        => 'Rule2',
			'terminal_id' => 1
		]);
		Rule::create([
			'id'          => 3,
			'name'        => 'Rule3',
			'terminal_id' => 1
		]);

		// Terminal user
		Terminal::create([
			'id'       => 1,
			'name'     => 'User1',
			'key'      => 'key',
			'password' => Hash::make('password'),
		]);
	}
}
 