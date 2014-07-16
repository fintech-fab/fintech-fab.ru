<?php


use FintechFab\ActionsCalc\Components\SendResults;

class CalcRequestTest extends CalcTestCase
{
	private $requestData;
	/**
	 * @var Mockery\MockInterface|SendResults
	 */
	private $mock;

	public function setUp()
	{
		parent::setUp();

		$this->mock = Mockery::mock('FintechFab\ActionsCalc\Components\SendResults');
		$this->requestData = array(
			'term'   => 1,
			'sid'  => 'im_hungry',
			'data' => json_encode(array('time' => '13.05')),
			'signal' => null,
		);

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

		$response = $this->call(
		'POST',
			'/actions-calc/getRequest',
			$this->requestData
		);

		print_r($response);
	}


	public function setDown()
	{
		Mockery::close();
	}

}