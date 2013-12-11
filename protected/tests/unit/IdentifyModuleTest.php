<?php
/**
 * Class IdentifyModuleTest
 */
class IdentifyModuleTest extends \PHPUnit_Framework_TestCase
{

	const IMAGE = 'k2jh434h5jh54==';

	public function setUp()
	{
		YiiBase::$enableIncludePath = false;
	}

	public function testIdentify()
	{
		//процедура логина
		$aRequest = array(
			'login'    => '12312123',
			'password' => '123'
		);

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = \CJSON::decode($sResult);

		$sToken = $aRequest['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aRequest['result']['instruction']);


		//процедура получения фото клиента
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aRequest['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aRequest['result']['instruction']);
		//проверяем наличие document=1
		$this->assertEquals(1, $aRequest['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aRequest['result']['title']);
		//проверяем наличие example
		$this->assertNotEmpty($aRequest['result']['example']);
		//проверяем наличие description
		$this->assertNotEmpty($aRequest['result']['description']);

		//процедура загрузки документа 1
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aRequest['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aRequest['result']['instruction']);
		//проверяем наличие document=1
		$this->assertEquals(2, $aRequest['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aRequest['result']['title']);
		//проверяем наличие example
		$this->assertNotEmpty($aRequest['result']['example']);
		//проверяем наличие description
		$this->assertNotEmpty($aRequest['result']['description']);

		//TODO прочие тесты

		//процедура загрузки последнего документа
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aRequest['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aRequest['result']['instruction']);
		//проверяем наличие done=1 - идентификация завершена
		$this->assertEquals(2, $aRequest['result']['done']);
	}

	//TODO неверный логин

	//TODO верный логин, следом image отправлен, а token нет (либо неверный токен)

	//TODO верный логин, следом token отправлен, а image нет (либо отправлена не картинка)

	/**
	 * @param $aRequestFields
	 *
	 * @return mixed
	 */
	private function _requestToCallback($aRequestFields)
	{
		$sLink = 'http://barsova.kreddy.popov/identify/';
		$ch = curl_init($sLink);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequestFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		return curl_exec($ch);

	}
}