<?php

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use FintechFab\ActionsCalc\Models\Terminal;

class CalcTestCase extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		Event::truncate();
		Terminal::truncate();
		Rule::truncate();
		Signal::truncate();

		Terminal::create(array(
			'name'  => 'test',
			'url'   => 'http://test',
			'queue' => 'queueTest',
			'key' => 'key',
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Иди кушай',
			'event_sid'   => 'im_hungry',
			'rule'        => 'time !>= 13.00 AND time !<= 14.00 AND have_money === true',
			'signal_sid'  => 'go_eat',
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Жди',
			'event_sid'   => 'im_hungry',
			'rule'        => 'time !< 13.00',
			'signal_sid'  => 'wait',
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Терпи до дома',
			'event_sid'   => 'im_hungry',
			'rule'        => 'time !> 14.00',
			'signal_sid'  => 'endure',
			'flag_active' => true,
		));
	}
} 