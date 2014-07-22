<?php

class RequestControllerTest extends TestCase
{
	public function testPostRequest() {

		$requstData = ['data' => json_encode(['test' => 1])];

		$response = $this->call('POST', '/actions-calc/getRequest', $requstData);
		$this->assertContains($requstData, $response->original);
	}
}
 