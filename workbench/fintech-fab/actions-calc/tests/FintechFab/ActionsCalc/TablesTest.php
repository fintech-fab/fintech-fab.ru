<?php

class TablesTest extends CalcTestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testCreteEvent()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_sid' => 'im_very_hungry',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableEvents/addData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testCreteEventErrorFormat()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_sid' => 'im_hungry%',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableEvents/addData/',
			$requestData
		);

		$this->assertEquals('Только буквы, цифры, тире и подчёткивания.', $response->original['errors']['event_sid']);
	}

	public function testCreteEventErrorUnique()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_sid' => 'im_hungry',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableEvents/addData/',
			$requestData
		);

		$this->assertEquals('Такой sid уже существует', $response->original['errors']['event_sid']);
	}

	public function testChangeEvent()
	{

		$requestData = array(
			'id'        => 1,
			'name'      => 'Тестовое название',
			'event_sid' => 'im_very_hungry',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableEvents/changeData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testChangeEventFailEventId()
	{

		$requestData = array(
			'id'        => 3,
			'name'      => 'Тестовое название',
			'event_sid' => 'im_very_hungry',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableEvents/changeData/',
			$requestData
		);

		$this->assertEquals('Событие не найдено', $response->original['message']);
	}

	public function testCreteSignal()
	{

		$requestData = array(
			'name'       => 'Тестовое название',
			'signal_sid' => 'go_to_cafe',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableSignals/addData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testCreteSignalErrorFormat()
	{

		$requestData = array(
			'name'       => 'Тестовое название',
			'signal_sid' => 'go_eat%',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableSignals/addData/',
			$requestData
		);

		$this->assertEquals('Только буквы, цифры, тире и подчёткивания.', $response->original['errors']['signal_sid']);
	}

	public function testCreteSignalErrorUnique()
	{

		$requestData = array(
			'name'       => 'Тестовое название',
			'signal_sid' => 'go_eat',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableSignals/addData/',
			$requestData
		);

		$this->assertEquals('Такой sid уже существует', $response->original['errors']['signal_sid']);
	}

	public function testChangeSignal()
	{

		$requestData = array(
			'id'         => 1,
			'name'       => 'Тестовое название',
			'signal_sid' => 'go_to_cafe',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableSignals/changeData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testChangeSignalFailSignalId()
	{

		$requestData = array(
			'id'         => 9,
			'name'       => 'Тестовое название',
			'signal_sid' => 'go_to_cafe',
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableSignals/changeData/',
			$requestData
		);

		$this->assertEquals('Сигнал не найден', $response->original['message']);
	}

	public function testCreteRule()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_id'  => 1,
			'rule'      => 'dept > 10000',
			'signal_id' => ['signal_id' => 1],
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableRules/addData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testCreteRuleErrorEventId()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_id'  => 100,
			'rule'      => 'dept > 10000',
			'signal_id' => ['signal_id' => 1],
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableRules/addData/',
			$requestData
		);

		$this->assertEquals('Такой sid не существует', $response->original['errors']['event_id']);
	}

	public function testCreteRuleErrorSignalId()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_id'  => 1,
			'rule'      => 'dept > 10000',
			'signal_id' => ['signal_id' => 1000],
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableRules/addData/',
			$requestData
		);

		$this->assertEquals('Такой sid не существует', $response->original['errors']['signal_id']['signal_id']);
	}

	public function testChangeRule()
	{

		$requestData = array(
			'id'        => 1,
			'name'      => 'Тестовое название',
			'event_id'  => 1,
			'rule'      => 'dept > 10000',
			'signal_id' => 1,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableRules/changeData/',
			$requestData
		);

		$this->assertEquals('Данные изменены', $response->original['message']);
	}

	public function testChangeRuleFailId()
	{

		$requestData = array(
			'name'      => 'Тестовое название',
			'event_id'  => 1,
			'rule'      => 'dept > 10000',
			'signal_id' => 1,
		);

		$response = $this->call(
			'POST',
			'/actions-calc/tableRules/changeData/',
			$requestData
		);

		$this->assertEquals('Какая-то ошибка, повторите попытку', $response->original['message']);
	}


}