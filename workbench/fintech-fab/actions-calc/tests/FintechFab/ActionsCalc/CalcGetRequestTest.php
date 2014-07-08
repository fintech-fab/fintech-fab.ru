<?php


class CalcGetRequestTest extends TestCase
{
	private $requestData;

	public function setUp()
	{
		parent::setUp();
		$this->requestData = array(
			'term'   => 1,
			'sid'  => 'im_hungry',
			'data' => json_encode(array('time' => '13.05')),
			'signal' => null,
		);

	}

	public function testGetRequest()
	{

		$this->call(
		'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);


	}

}