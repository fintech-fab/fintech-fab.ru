<?php

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\IncomeEvent;
use FintechFab\ActionsCalc\Models\ResultSignal;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use FintechFab\ActionsCalc\Models\Terminal;

class CalcTestCase extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		Config::set('termId', 1);

		IncomeEvent::truncate();
		Event::truncate();
		Terminal::truncate();
		Rule::truncate();
		ResultSignal::truncate();
		Signal::truncate();

		Terminal::create(array(
			'name'  => 'test',
			'url'   => 'http://test',
			'queue' => 'queueTest',
			'key'   => 'key',
		));

		Event::create(array(
			'terminal_id' => 1,
			'name'        => 'Хочу есть',
			'event_sid'   => 'im_hungry',
		));

		Signal::create(array(
			'terminal_id' => 1,
			'name'        => 'Иди кушай',
			'signal_sid'  => 'go_eat',
		));

		Signal::create(array(
			'terminal_id' => 1,
			'name'        => 'Жди',
			'signal_sid'  => 'wait',
		));

		Signal::create(array(
			'terminal_id' => 1,
			'name'        => 'Терпи до дома',
			'signal_sid'  => 'endure',
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Иди кушай',
			'event_id'    => 1,
			'rule'        => 'time >= 13.00 AND time <= 14.00 AND have_money === true',
			'signal_id'   => 1,
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Жди',
			'event_id'    => 1,
			'rule'        => 'time < 13.00',
			'signal_id'   => 2,
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Терпи до дома',
			'event_id'    => 1,
			'rule'        => 'time > 14.00',
			'signal_id'   => 3,
			'flag_active' => true,
		));
	}
} 