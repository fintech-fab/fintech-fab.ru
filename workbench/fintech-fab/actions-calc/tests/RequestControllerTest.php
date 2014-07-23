<?php

class RequestControllerTest extends TestSetUp
{
	// Request post data
	private $aRequestData = [];

	public function setUp()
	{
		$this->aRequestData = [
			'auth_sign' => 'sign',
			'event_sid' => 'under_rain',
			'data'      => json_encode(['test' => 1])
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

	public function testAuthenticateSuccess()
	{
		// (terminal_id|event|key)
		$sCompareString = sha1("1|under_rain|key");
		$iTerminalId = Config::get('app.terminal_id');
		$sUserString = Config::get('app.key');
	}
}
 