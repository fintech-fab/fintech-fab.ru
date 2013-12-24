<?php
/**
 * Class IdentifyModuleTest
 * @method assertEmpty
 * @method assertNotEmpty
 * @method assertTrue
 * @method assertEquals
 */

class IdentifyModuleTest extends \PHPUnit_Framework_TestCase
{

	const IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAAFkAAAAXCAIAAADvO3TkAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFySURBVFhH7ZTbsYMwDESpi4Koh2poJsVwLRtrV8LiOslkJkx8vmx59dohmfZBZXgBhhdgeAF+zYttmeb1cVwc3otlOrNsx2MXj3VuZqQpnqz0EdJ4kRnBdyELhf5d8+VeXHwZ/V5IqIKdKJr0rPH55EWSpUcJZFJUEymJiqGfJhVQ8AiwVpEkFA7N6PWCNqFHOfnerZhAFcro5VLOdDnabkvtb4L1xF3asxEiQJBTDX1e+A51Jon7ulEr54VKzIXXUlTB0jpCDrZmi4kU/V446mt9UjUPzDzrhe1ZgtgCQqvLNDcF73uh8zaxs7W0bk2VmIuKSA2FHJTTazfveXG6n8BEkfRlL+SoQVWCf2fLFaAwDZne/84SAuUxj3mg5avQ5dN2ZhpzIZHWntdVPeCGbj3gGpcsBNPtpMgEXnwlsjFtEe50jRij3hvu5IXdwjnTS2zFrbxwv4VXPorLj+leXnyW4QUYXoDhBRhegOFFZd//AF7fkhtTwXjVAAAAAElFTkSuQmCC';

	const NOT_IMAGE = 'fgfhfghgf';

	public function setUp()
	{
		YiiBase::$enableIncludePath = false;
	}

	public function tearDown()
	{
		ob_flush();
	}

	// проверка полной процедуры идентификации
	public function testIdentify()
	{
		//процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa12345",
		);

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = CJSON::decode($sResult);

		$sToken = $aResult['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], ('Код результата не равен 0: code=' . $aResult['code']));
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!' . $sResult);
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');


		//процедура получения фото клиента
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);

		$sToken = $aResult['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], "Нет инструкции!");
		//проверяем наличие document=1
		$this->assertEquals(1, $aResult['result']['document'], "Документ не равен 1: document=" . $aResult['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aResult['result']['title'], "Нет заголовка");
		//проверяем наличие example
		$this->assertNotEmpty($aResult['result']['example'], "Нет примера");
		//проверяем наличие description
		$this->assertNotEmpty($aResult['result']['description'], "Нет описания");

		//процедура загрузки документа 1
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], "Нет инструкции!");
		//проверяем наличие document=2
		$this->assertEquals(2, $aResult['result']['document'], "Документ не равен 2: document=" . $aResult['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aResult['result']['title'], "Нет заголовка");
		//проверяем наличие example
		$this->assertNotEmpty($aResult['result']['example'], "Нет примера");
		//проверяем наличие description
		$this->assertNotEmpty($aResult['result']['description'], "Нет описания");

		//процедура загрузки документа 2
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], "Нет инструкции!");
		//проверяем наличие document=3
		$this->assertEquals(3, $aResult['result']['document'], "Документ не равен 3: document=" . $aResult['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aResult['result']['title'], "Нет заголовка");
		//проверяем наличие example
		$this->assertNotEmpty($aResult['result']['example'], "Нет примера");
		//проверяем наличие description
		$this->assertNotEmpty($aResult['result']['description'], "Нет описания");

		//процедура загрузки документа 3
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], "Нет инструкции!");
		//проверяем наличие document=4
		$this->assertEquals(4, $aResult['result']['document'], "Документ не равен 4: document=" . $aResult['result']['document']);
		//проверяем наличие title
		$this->assertNotEmpty($aResult['result']['title'], "Нет заголовка");
		//проверяем наличие example
		$this->assertNotEmpty($aResult['result']['example'], "Нет примера");
		//проверяем наличие description
		$this->assertNotEmpty($aResult['result']['description'], "Нет описания");

		//процедура загрузки последнего документа
		$aRequest = array(
			'token' => $sToken,
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		$this->assertEquals(0, $aResult['code'], 'Код результата не равен 0: code=' . $aResult['code']);
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');
		//проверяем наличие done=1 - идентификация завершена
		$this->assertEquals(1, $aResult['result']['done'], 'Нет флажка, что идентификация завершена');
		//*/
	}

	// неверные данные для авторизации
	public function testIdentifyInvalidLogin()
	{
		//процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa1234523435",
		);

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность логина
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}

	// не введён логин
	public function testIdentifyNoLogin()
	{
		//процедура логина
		$aRequest = array(
			'password' => "Aa1234523435",
		);

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность логина
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}

	// не введён пароль
	public function testIdentifyNoPassword()
	{
		//процедура логина
		$aRequest = array(
			'login' => "9513570000",
		);

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность логина
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}

	// отправлен пустой запрос
	public function testIdentifyNoLoginPassword()
	{
		// отправляем пустой запрос
		$aRequest = array();

		$sResult = $this->_requestToCallback($aRequest);

		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность логина
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}

	//верный логин, следом image отправлен, а токен - неверный
	public function testIdentifyImageWithInvalidToken()
	{
		//процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa12345",
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], ('Код результата не равен 0: code=' . $aResult['code']));
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');

		//процедура получения фото клиента
		$aRequest = array(
			'token' => "gdf433!5",
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность запроса
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
		//*/
	}

	///верный логин, следом image отправлен, а token нет
	public function testIdentifyImageWithoutToken()
	{
		//процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa12345",
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], ('Код результата не равен 0: code=' . $aResult['code']));
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');

		//процедура получения фото клиента
		$aRequest = array(
			'image' => self::IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность запроса
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}

	// верный логин, следом token отправлен, а отправлена не картинка
	public function testIdentifyTokenWithInvalidImage()
	{
		//процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa12345",
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], ('Код результата не равен 0: code=' . $aResult['code']));
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');

		//процедура получения фото клиента
		$aRequest = array(
			'token' => $sToken,
			'image' => self::NOT_IMAGE,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность запроса
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
		//
	}

	// верный логин, следом token отправлен, а image нет
	public function testIdentifyTokenWithoutImage()
	{
		///процедура логина
		$aRequest = array(
			'login'    => "9513570000",
			'password' => "Aa12345",
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);
		$sToken = $aResult['result']['token'];

		//проверяем успешность логина
		$this->assertEquals(0, $aResult['code'], ('Код результата не равен 0: code=' . $aResult['code']));
		//проверяем наличие токена
		$this->assertNotEmpty($sToken, 'Токен не получен!');
		//проверяем наличие инструкции
		$this->assertNotEmpty($aResult['result']['instruction'], 'Нет инструкции');

		//процедура получения фото клиента
		$aRequest = array(
			'token' => $sToken,
		);

		$sResult = $this->_requestToCallback($aRequest);
		$aResult = CJSON::decode($sResult);

		//проверяем неуспешность запроса
		$this->assertEquals(-1, $aResult['code'], ('Код результата не равен -1: code=' . $aResult['code']));
		//проверяем наличие сообщения об ошибке
		$this->assertNotEmpty($aResult['message'], 'Сообщения об ошибке нет!');
	}


	/**
	 * @param $aRequestFields
	 *
	 * @return mixed
	 */
	private function _requestToCallback($aRequestFields)
	{
		$sLink = rtrim(Yii::app()->params['mainUrl'], '/') . '/identify';
		$ch = curl_init($sLink);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequestFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		return curl_exec($ch);

	}
}
