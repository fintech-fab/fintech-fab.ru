<?php

use FintechFab\ActionsCalc\Components\AuthHandler;

class RequestControllerTest extends TestSetUp
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testRequestDataIn()
	{
		$this->call('POST', '/actions-calc/getRequest', $this->aRequestData);
		$this->assertNotEmpty($this->aRequestData['auth_sign']);
		$this->assertNotEmpty($this->aRequestData['event_sid']);
		$this->assertResponseOk();
	}

	public function testAuthSignature()
	{
		$this->assertTrue(AuthHandler::checkSign($this->aRequestData), 'Signature test failed');
	}

	public function testAuthFailAndRedirect()
	{
		Route::enableFilters();
		Config::set('ff-actions-calc::app.terminal_id', 0);
		$this->call('POST', '/actions-calc/getRequest', $this->aRequestData);
		$this->assertRedirectedToRoute('login');
	}
}
 