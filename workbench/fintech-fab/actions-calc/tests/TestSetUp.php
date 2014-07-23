<?php

use FintechFab\ActionsCalc\Models\Terminal;
use FintechFab\ActionsCalc\Models\Rule;

class TestSetUp extends TestCase
{
	public function setUp() {
		parent::setUp();

		// Clearing tables on every test
		Terminal::truncate();
		Rule::truncate();

		// Fill in tables on every test
		// Rules
		Rule::create(['id' => 1, 'name' => 'Rule1', 'terminal_id' => 1]);
		Rule::create(['id' => 2, 'name' => 'Rule2', 'terminal_id' => 1]);
		Rule::create(['id' => 3, 'name' => 'Rule3', 'terminal_id' => 1]);

		// Terminal user
		Terminal::create(['id' => 1, 'name' => 'User1']);
	}
}
 