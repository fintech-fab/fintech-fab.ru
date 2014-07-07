<?php

use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Signal;
use FintechFab\ActionsCalc\Models\Terminal;

class RulesTableSeeder extends Seeder
{

	public function run()
	{
		Terminal::truncate();
		Rule::truncate();
		Signal::truncate();

		Terminal::create(array(
			'name'  => 'test',
			'url'   => '',
			'queue' => '',
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name' => 'Иди кушай',
			'event_sid'   => 'im_hungry',
			'rule' => 'time !>= 13.00 AND time !<= 14.00 AND have_money === true',
			'signal_sid'  => 'go_eat',
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name' => 'Жди',
			'event_sid'   => 'im_hungry',
			'rule' => 'time !< 13.00',
			'signal_sid'  => 'wait',
			'flag_active' => true,
		));

		Rule::create(array(
			'terminal_id' => 1,
			'name'        => 'Терпи до дома',
			'event_sid'   => 'im_hungry',
			'rule' => 'time !> 14.00',
			'signal_sid'  => 'endure',
			'flag_active' => true,
		));

		Signal::create(array(
			'name'       => 'Поешь',
			'signal_sid' => 'go_eat',
		));

		Signal::create(array(
			'name'       => 'Жди',
			'signal_sid' => 'wait',
		));
	}

}