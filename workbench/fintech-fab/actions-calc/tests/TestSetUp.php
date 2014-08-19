<?php

use FintechFab\ActionsCalc\Models\Terminal;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Signal;
use FintechFab\ActionsCalc\Models\RegisterEvent;
use FintechFab\ActionsCalc\Models\RegisterSignal;

class TestSetUp extends TestCase
{
	protected $aRequestData;
	protected $sSignature;

	// Set here:
	// 1. database seed and truncate
	// 2. request data
	// 3. client signature
	public function setUp()
	{
		parent::setUp();
		// Fill in tables on every testg
		// Clearing tables on every test
		Terminal::truncate();
		Rule::truncate();
		Event::truncate();
		Signal::truncate();
		RegisterEvent::truncate();
		RegisterSignal::truncate();

		$this->sSignature = sha1("1|under_rain|key");
		$this->aRequestData = [
			'terminal_id' => 1,
			'event_sid'   => 'under_rain',
			'data'        => json_encode([
				'all_wet' => true,
				'time'    => '15:05',
			]),
			'auth_sign' => $this->sSignature
		];

		// Terminal user
		Terminal::create([
			'id'            => 1,
			'name'          => 'Терминал 1',
			'key'           => 'key',
			'password'      => Hash::make('password'),
			'flag_active'   => true,
			'url'           => 'http://ya.ru',
			'foreign_queue' => 'test_queue',
			'foreign_job'   => 'Some\Foreign\Job',
		]);

		// Events
		Event::create([
			'id'          => 1,
			'event_sid'   => 'under_rain',
			'name'        => 'Событие раз',
			'terminal_id' => 1
		]);

		Event::create([
			'id'          => 2,
			'event_sid'   => 'stay_smoking',
			'name'        => 'Событие два',
			'terminal_id' => 1
		]);

		Event::create([
			'id'          => 3,
			'event_sid'   => 'under_hail',
			'name'        => 'Событие три',
			'terminal_id' => 1
		]);

		// Rules
		Rule::create([
			'id'          => 1,
			'name'        => 'Правило раз',
			'rule'        => '[{"name":"cold","value":true, "operator":"OP_BOOL"},
								{"rule_operator":"AND", "name":"sopli", "value":true, "operator":"OP_BOOL"},
								{"rule_operator":"AND", "name":"all_wet", "value":true, "operator":"OP_BOOL"},
								{"rule_operator":"AND", "name":"time", "value":15.05, "operator":"OP_GREATER"}]',
			'terminal_id' => 1,
			'event_id'    => 1,
			'signal_id'   => 1,
			'flag_active' => true,
		]);
		Rule::create([
			'id'          => 2,
			'name'        => 'Правило два',
			'rule'        => '[{"name":"sopli","value":true, "operator":"OP_BOOL"}]',
			'terminal_id' => 1,
			'event_id'    => 1,
			'signal_id'   => 3,
			'flag_active' => true,
		]);
		Rule::create([
			'id'          => 3,
			'name'        => 'Правило три',
			'rule'        => '[{"name":"time","value":16.05, "operator":"OP_LESS_OR_EQUAL"}]',
			'terminal_id' => 1,
			'event_id'    => 1,
			'signal_id'   => 2,
			'flag_active' => true,
		]);

		// Signals
		Signal::create([
			'id'          => 1,
			'name'        => 'Орать мама',
			'signal_sid'  => 'cry_mommy',
			'terminal_id' => 1,
		]);
		Signal::create([
			'id'          => 2,
			'name'        => 'Беги домой',
			'signal_sid'  => 'run_home',
			'terminal_id' => 1,
		]);
		Signal::create([
			'id'          => 3,
			'name'        => 'Просто стой',
			'signal_sid'  => 'wasted',
			'terminal_id' => 1,
		]);

	}
}
 