<?php


use FintechFab\ActionsCalc\Components\SendResults;

class CalcRequestTest extends CalcTestCase
{
	/**
	 * @var Mockery\MockInterface|SendResults
	 */
	private $mock;

	public function setUp()
	{
		parent::setUp();

		$this->mock = Mockery::mock('FintechFab\ActionsCalc\Components\SendResults');
	}

	public function testGetRequest()
	{

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('makeCurl')
				->withArgs(['http://test', 'go_eat']);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'go_eat']);

			return $this->mock;

		});

		$requestData = array(
			'term'   => 1,
			'sid'    => 'im_hungry',
			'data'   => json_encode(array('time' => '13.05')),
			'signal' => null,
		);

		$response = $this->call(
		'POST',
			'/actions-calc/getRequest',
			$requestData
		);

	}

	public function testGetRequest2()
	{

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('makeCurl')
				->withArgs(['http://test', 'wait']);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'wait']);

			return $this->mock;

		});

		$requestData = array(
			'term'   => 1,
			'sid'    => 'im_hungry',
			'data'   => json_encode(array('time' => '12.05')),
			'signal' => null,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

	}

	public function testGetRequest3()
	{

		App::bind('FintechFab\ActionsCalc\Components\SendResults', function () {
			$this->mock
				->shouldReceive('makeCurl')
				->withArgs(['http://test', 'endure']);
			$this->mock
				->shouldReceive('sendQueue')
				->withArgs(['queueTest', 'endure']);

			return $this->mock;

		});

		$requestData = array(
			'term'   => 1,
			'sid'    => 'im_hungry',
			'data'   => json_encode(array('time' => '14.30')),
			'signal' => null,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/getRequest',
			$requestData
		);

	}

	public function setDown()
	{
		Mockery::close();
	}

}