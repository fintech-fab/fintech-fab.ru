<?php

use FintechFab\ActionsCalc\Models\Terminal;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Event;

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
		Event::truncate();

		// Fill in tables on every testg
		// Events
		Event::create([
			'id'          => 1,
			'event_sid'   => 'under_rain',
			'name'        => 'Событие раз',
			'terminal_id' => 1
		]);

		// Rules
		Rule::create([
			'id'          => 1,
			'rule'        => 'cold=true[AND]sopli=true[AND]all_wet=true[AND]time!>14:00',
			'name'        => 'Правило раз',
			'terminal_id' => 1,
			'event_id'    => 1,
		]);
		Rule::create([
			'id'          => 2,
			'name'        => 'Правило два',
			'terminal_id' => 1,
			'event_id'    => 1,
		]);
		Rule::create([
			'id'          => 3,
			'name'        => 'Правило три',
			'terminal_id' => 1,
			'event_id'    => 1,
		]);

		// Terminal user
		Terminal::create([
			'id'       => 1,
			'name'     => 'Терминал 1',
			'key'      => 'key',
			'password' => Hash::make('password'),
		]);
	}
}
 