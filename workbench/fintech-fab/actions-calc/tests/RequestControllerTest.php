<?php

class RequestControllerTest extends TestSetUp
{


	function __construct()
	{
		parent::setUp();
	}

	public function testPostRequest() {

		$requstData = ['data' => json_encode(['test' => 1])];

		$response = $this->call('POST', '/actions-calc/getRequest', $requstData);
		$this->assertContains(json_encode(['test' => 2]), $response->original);
	}
}
 