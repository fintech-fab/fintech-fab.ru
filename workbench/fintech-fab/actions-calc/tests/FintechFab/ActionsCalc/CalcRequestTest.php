<?php


use FintechFab\ActionsCalc\Components\SendResults;

class CalcRequestTest extends CalcTestCase
{
	/**
	 * @var Mockery\MockInterface|SendResults
	 */
	private $mock;
	private $sign;
	private $requestData;

	public function setUp()
	{
		parent::setUp();

		$this->mock = Mockery::mock('FintechFab\ActionsCalc\Components\SendResults');
		$this->sign = md5('terminal=1|event=im_hungry|key');
	}

	public function testGetRequest1()
	{

		$requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '13.05')),
			'sign'  => $this->sign,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

		$this->assertContains(json_encode(['countFitRules' => 0]), $response->original);
	}

	public function testGetRequest2()
	{
		$this->requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '12.05')),
			'sign'  => $this->sign,
		);

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('sendHttp')
				->withArgs(['http://test', '1', $this->requestData]);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'wait', $this->requestData]);

			return $this->mock;

		});


		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);

		$this->assertContains(json_encode(['countFitRules' => 1]), $response->original);
	}

	public function testGetRequest3()
	{
		$this->requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '14.30')),
			'sign'  => $this->sign,
		);

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('sendHttp')
				->withArgs(['http://test', '1', $this->requestData]);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'endure', $this->requestData]);

			return $this->mock;

		});


		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);

		$this->assertContains(json_encode(['countFitRules' => 1]), $response->original);
	}

	public function testGetRequest4()
	{

		$requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '13.30', 'have_money' => false)),
			'sign'  => $this->sign,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

		$this->assertContains(json_encode(['countFitRules' => 0]), $response->original);
	}

	public function testGetRequest5()
	{
		$this->requestData = array(
			'term'  => 1,
			'event' => 'im_hungry',
			'data'  => json_encode(array('time' => '13.30', 'have_money' => true)),
			'sign'  => $this->sign,
		);

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('sendHttp')
				->withArgs(['http://test', '1', $this->requestData]);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'go_eat', $this->requestData]);

			return $this->mock;

		});


		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);

		$this->assertContains(json_encode(['countFitRules' => 1]), $response->original);
	}

	public function setDown()
	{
		Mockery::close();
	}

}