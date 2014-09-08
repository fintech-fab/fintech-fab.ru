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
		$this->call('POST', '/actions-calc', $this->aRequestData);
		$this->assertNotEmpty($this->aRequestData['auth_sign']);
		$this->assertNotEmpty($this->aRequestData['event_sid']);
		$this->assertResponseOk();
	}

	public function testUnauthorizedError()
	{

		$this->aRequestData = [
			'terminal_id' => 7,
			'event_sid'   => 'under_rain',
			'data'        => json_encode([
				'all_wet' => true,
				'time'    => '15:05',
			]),
			'auth_sign'   => $this->sSignature
		];

		/**
		 * @var \Illuminate\Http\JsonResponse $response
		 */
		$response = $this->call('POST', '/actions-calc', $this->aRequestData);
		$this->assertContains('{"status":"error","message":"401 Wrong signature. Unauthorized."}', json_encode($response->getData()));
	}

	public function testAuthSignature()
	{
		$this->assertTrue(AuthHandler::checkSign($this->aRequestData), 'Signature test failed');
	}
}
 