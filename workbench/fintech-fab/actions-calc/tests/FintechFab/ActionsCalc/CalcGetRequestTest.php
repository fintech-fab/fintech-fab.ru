<?php


class CalcGetRequestTest extends TestCase
{
	private $requestData;

	public function setUp()
	{
		parent::setUp();
		$this->requestData = array(
			'term'   => 1,
			'sid'    => 'хочу есть',
			'data'   => null,
			'signal' => null,
		);

	}

	public function testGetRequest()
	{

		$resp = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);


	}

}