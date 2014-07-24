<?php

use FintechFab\ActionsCalc\Components\AuthHandler;

class RequestControllerTest extends TestSetUp
{
	public function setUp()
	{
		parent::setUp();
		Route::enableFilters();
	}

	public function testRequestDataIn()
	{
		$response = $this->call('POST', '/actions-calc/getRequest', $this->aRequestData);
		$this->assertNotEmpty($this->aRequestData['auth_sign']);
		$this->assertNotEmpty($this->aRequestData['event_sid']);
		$this->assertContains(json_encode(['test' => 2]), $response->original);
	}

	public function testSignature()
	{
		$this->assertTrue(AuthHandler::checkSign($this->aRequestData));
	}

	public function testAuthFailAndRedirect()
	{
		Config::set('ff-actions-calc::app.terminal_id', 0);
		$this->call('POST', '/actions-calc/getRequest', $this->aRequestData);
		$this->assertRedirectedToRoute('login');
	}
}
 