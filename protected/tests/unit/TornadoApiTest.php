<?php

/**
 * Class TornadoApiTest
 * @method static assertEmpty
 * @method static assertNotEmpty
 * @method static assertTrue
 * @method static assertEquals
 */
class TornadoApiTest extends \PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		YiiBase::$enableIncludePath = false;
	}

	public function tearDown()
	{
		ob_flush();
	}

	/**
	 * Проверяем, что получение каналов происходит успешно
	 */
	public function testGetChannels()
	{
		$sChannels = Yii::app()->productsChannels->getChannelsForTornadoApi('mobile');
		$this->assertEquals('8_9_10_27', $sChannels);

		$sChannels = Yii::app()->productsChannels->getChannelsForTornadoApi('card');
		$this->assertEquals('20', $sChannels);

		$sChannels = Yii::app()->productsChannels->getChannelsForTornadoApi('sometext');
		$this->assertFalse($sChannels);
	}

	/**
	 * Проверяем, что получение типа оплаты происходит успешно
	 */
	public function testGetPayType()
	{
		$iPayType = Yii::app()->tornadoApi->getPayTypeByName('postpaid');
		$this->assertEquals(4, $iPayType);

		$iPayType = Yii::app()->tornadoApi->getPayTypeByName('prepaid');
		$this->assertEquals(3, $iPayType);

		$iPayType = Yii::app()->tornadoApi->getPayTypeByName('paid');
		$this->assertFalse($iPayType);
	}

	/**
	 * Проверяем корректность получения продукта по типу
	 */
	public function testGetProductByCostTest()
	{
		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(2000, 4);
		$this->assertEquals(35, $iProduct);

		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(3000, 4);
		$this->assertEquals(36, $iProduct);

		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(2000, 3);
		$this->assertEquals(31, $iProduct);

		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(3000, 3);
		$this->assertEquals(32, $iProduct);

		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(3500, 4);
		$this->assertEmpty($iProduct);

		$iProduct = Yii::app()->productsChannels->getProductByAmountAndType(3000, 5);
		$this->assertEmpty($iProduct);
	}

	public function testRegisterProcess()
	{

		$sPhone = '9505555551';

		// данные регистрации
		$aRequest = array(
			'last_name'      => 'Петров',
			'first_name'     => 'Петр',
			'third_name'     => 'Петрович',
			'email'          => '4some-test-user@some-domain.ru',
			'phone'          => '7' . $sPhone,
			'agree'          => '1',
			'product'        => '3000',
			'pay_type'       => 'postpaid',
			'channel'        => 'card',
			'email_back_url' => 'https://kreddy.ru',
		);

		// соберем данные для проверки подписи
		ksort($aRequest);
		$sData = implode('', $aRequest);

		// генерируем подпись
		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запрашиваем регистрацию
		$sResult = $this->_request('signup', $aRequest);

		$aResult = CJSON::decode($sResult);

		$this->assertEquals('0', $aResult['code']);

		$sToken = $aResult['token'];

		$this->assertNotEmpty($sToken);


		// подготовим следующий запрос
		$aRequest = array(
			'token' => $sToken,
		);

		// соберем данные для проверки подписи
		ksort($aRequest);
		$sData = implode('', $aRequest);

		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запросим переотправку СМС
		$sResult = $this->_request('resend/sms', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что нельзя, не прошло 3 минуты
		$this->assertEquals(-7, $aResult['code']);

		// запросим переотправку email
		$sResult = $this->_request('resend/email', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что нельзя, не прошло 3 минуты
		$this->assertEquals(-7, $aResult['code']);

		// подготовим следующий запрос
		$aRequest = array(
			'sms_code'   => '123',
			'email_code' => '123',
			'token'      => $sToken,
		);

		ksort($aRequest);
		$sData = implode('', $aRequest);

		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запросим переотправку email
		$sResult = $this->_request('client/code', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что ошибка проверки кодов
		$this->assertEquals(-5, $aResult['code']);


		// получим данные клиента
		$oClient = ClientData::model()->scopePhone($sPhone)->find();

		$this->assertEquals(4, $oClient->pay_type);

		// подготовим следующий запрос
		$aRequest = array(
			'sms_code'   => $oClient->sms_code,
			'email_code' => $oClient->email_code,
			'token'      => $sToken,
		);

		ksort($aRequest);
		$sData = implode('', $aRequest);

		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запросим переотправку email
		$sResult = $this->_request('client/code', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что все ОК
		$this->assertEquals(0, $aResult['code']);

		$this->assertNotEmpty($aResult['redirect_url']);

		$oClient->refresh();

		$this->assertNotEmpty($oClient->api_token);

	}

	/**
	 * Проверим выдачу ошибок валидации
	 */
	public function testValidationErrors_One()
	{

		$sPhone = '9505555555';

		// данные регистрации
		$aRequest = array(
			'last_name'      => 'Петров1',
			'first_name'     => 'Петрg',
			'third_name'     => 'Петрович',
			'email'          => 'some-test-user@some-domain.ru',
			'phone'          => '7' . $sPhone,
			'agree'          => '1',
			'product'        => '30004',
			'pay_type'       => 'postpaid',
			'channel'        => 'card',
			'email_back_url' => 'https://kreddy.ru',

		);

		// соберем данные для проверки подписи
		ksort($aRequest);
		$sData = implode('', $aRequest);

		// генерируем подпись
		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запрашиваем регистрацию
		$sResult = $this->_request('signup', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что ошибка валидации
		$this->assertEquals('-2', $aResult['code']);

		// проверим невалидные поля
		$aNoValid = explode(',', $aResult['no_valid']);
		$this->assertContains('last_name', $aNoValid);
		$this->assertContains('first_name', $aNoValid);
		$this->assertContains('product', $aNoValid);
	}

	/**
	 * Проверим выдачу ошибок валидации
	 */
	public function testValidationErrors_Two()
	{
		// данные регистрации
		$aRequest = array(
			'last_name'      => '',
			'first_name'     => '',
			'third_name'     => '',
			'email'          => '',
			'phone'          => '',
			'agree'          => '',
			'product'        => '',
			'pay_type'       => '',
			'channel'        => '',
			'email_back_url' => '',

		);

		// соберем данные для проверки подписи
		ksort($aRequest);
		$sData = implode('', $aRequest);

		// генерируем подпись
		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		$aRequest['sign'] = $sSign;

		// запрашиваем регистрацию
		$sResult = $this->_request('signup', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что ошибка валидации
		$this->assertEquals('-2', $aResult['code']);

		// проверим невалидные поля
		$aNoValid = explode(',', $aResult['no_valid']);
		$this->assertContains('last_name', $aNoValid);
		$this->assertContains('first_name', $aNoValid);
		$this->assertContains('third_name', $aNoValid);
		$this->assertContains('email', $aNoValid);
		$this->assertContains('phone', $aNoValid);
		$this->assertContains('agree', $aNoValid);
		$this->assertContains('product', $aNoValid);
		$this->assertContains('pay_type', $aNoValid);
		$this->assertContains('channel', $aNoValid);

	}

	/**
	 * Проверим ответ на неправильную подпись
	 */
	public function testSignError()
	{

		$sPhone = '9505555555';

		// данные регистрации
		$aRequest = array(
			'last_name'      => 'Петров',
			'first_name'     => 'Петр',
			'third_name'     => 'Петрович',
			'email'          => 'some-test-user@some-domain.ru',
			'phone'          => '7' . $sPhone,
			'agree'          => '1',
			'product'        => '3000',
			'pay_type'       => 'postpaid',
			'channel'        => 'card',
			'email_back_url' => 'https://kreddy.ru',

		);

		// соберем данные для проверки подписи
		ksort($aRequest);
		$sData = implode('', $aRequest);

		// генерируем подпись
		$sSign = Yii::app()->tornadoApi->generateSign($sData);

		// подменим данные
		$aRequest['last_name'] = 'Иванов';

		$aRequest['sign'] = $sSign;

		// запрашиваем регистрацию
		$sResult = $this->_request('signup', $aRequest);

		$aResult = CJSON::decode($sResult);

		// убедимся, что ответ "подпись неверна"
		$this->assertEquals('-3', $aResult['code']);


	}

	/**
	 * @param $sPath
	 * @param $aRequestFields
	 *
	 * @return mixed
	 */
	private function _request($sPath, $aRequestFields)
	{
		$sLink = rtrim(Yii::app()->params['mainUrl'], '/') . '/api/tornado/' . $sPath;

		//передаем параметр test, он требуется API для тестирования без подключения к реальному API admin.kreddy
		$aRequestFields['test'] = true;
		$ch = curl_init($sLink);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aRequestFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		return curl_exec($ch);

	}
}
