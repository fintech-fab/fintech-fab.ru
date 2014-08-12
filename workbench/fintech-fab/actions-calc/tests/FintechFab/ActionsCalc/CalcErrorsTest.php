<?php

class CalcErrorsTest extends CalcTestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testSignError()
	{
		$sign = md5('terminal=1|event=im_hungry|errorKey');
		$requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '13.05')),
			'sign'  => $sign,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

		$this->assertContains(json_encode(['error' => 'Auth error']), $response->original);
	}

	public function testEventError()
	{
		$sign = md5('terminal=1|event=im_very_hungry|key');
		$requestData = array(
			'term'  => 1,
			'event' => 'im_very_hungry',
			'data'  => json_encode(array('time' => '13.05')),
			'sign'  => $sign,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

		$this->assertContains(json_encode(['error' => 'Unknown event']), $response->original);
	}

	public function testJsonError()
	{
		$sign = md5('terminal=1|event=im_very_hungry|key');
		$requestData = array(
			'term'  => 1,
			'event' => 'im_very_hungry',
			'data'  => "{'time' => '13.05'}",
			'sign'  => $sign,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

		$this->assertContains(json_encode(['error' => 'JSON error']), $response->original);
	}
}