<?php

use \Mockery as m;
use FintechFab\ActionsCalc\Components\ResultHandler;

/**
 * Class ResultHandlerTest
 *
 * @property array         $_args
 * @property \Mockery\Mock $_mock
 */
class ResultHandlerTest extends TestSetUp
{
	private $_args;
	private $_mock;

	public function setUp()
	{
		parent::setUp();
		$this->_mock = m::mock(ResultHandler::class . "[sendHttpToQueue, resultToQueue]");
	}

	public function testRequestCalculationsRaz()
	{
		$this->_mock->shouldReceive('resultToQueue')->once();
		$this->_mock->shouldReceive('sendHttpToQueue')
			->once()
			->withArgs(['http://ya.ru', 2]);

		$this->app->instance(ResultHandler::class, $this->_mock);

		$this->_args = [
			'event_sid'   => 'under_rain',
			'terminal_id' => 1,
			'auth_sign'   => $this->sSignature,
			'data'        => '{"time":15.05, "all_wet":true}',
		];

		/**
		 * @var \Illuminate\Http\JsonResponse $jsonResponse
		 */
		$jsonResponse = $this->call('POST', 'actions-calc/getRequest', $this->_args);
		$this->assertContains(json_encode(['status' => 'success', 'fittedRulesCount' => 1]), $jsonResponse->getContent());
	}

	public function setDown()
	{
		m::close();
	}
}
 