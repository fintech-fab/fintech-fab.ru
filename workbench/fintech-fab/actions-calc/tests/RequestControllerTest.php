<?php

use FintechFab\ActionsCalc\Components\AuthHandler;

class RequestControllerTest extends TestSetUp
{
	// Request post data
	private $aRequestData;

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
}
 