<?php


class CalcGetRequestTest extends TestCase
{

	public function setUp()
	{
		parent::setUp();

	}

	public function testGetRequest()
	{

		$resp = $this->call(
			'GET',
			'/actions-calc/getRequest'
		);


	}

}