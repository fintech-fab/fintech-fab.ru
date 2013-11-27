<?php
/**
 * Class ModelsTest
 * @method assertEmpty
 * @method assertNotEmpty
 *
 * @package \Codeception\TestCase\Test
 */

class AccountModelsTest extends \PHPUnit_Framework_TestCase
{

	protected function setUp()
	{
		YiiBase::$enableIncludePath = false;
	}

	/**
	 * @dataProvider validPhoneProvider
	 */

	public function testAccountPhoneValid($phone)
	{

		$aPostData = array(
			'username' => $phone,
		);


		$oForm = new AccountLoginForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('username'), print_r($oForm->getError('username'), true));


	}

	/**
	 * @dataProvider noValidPhoneProvider
	 */

	public function testAccountPhoneNoValid($phone)
	{

		$aPostData = array(
			'username' => $phone,
		);
		$oForm = new AccountLoginForm();
		$oForm->setAttributes($aPostData);
		$oForm->validate();
		$this->assertNotEmpty($oForm->getError('username'), print_r($oForm->getError('username'), true));
	}

	/**
	 * @dataProvider validPhoneProvider
	 */

	public function testResetPasswordPhoneValid($phone)
	{

		$aPostData = array(
			'phone' => $phone,
		);


		$oForm = new AccountResetPasswordForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('phone'), print_r($oForm->getError('phone'), true));


	}

	/**
	 * @dataProvider noValidPhoneProvider
	 */

	public function testResetPasswordPhoneNoValid($phone)
	{
		$aPostData = array(
			'phone' => $phone,
		);

		$oForm = new AccountResetPasswordForm();
		$oForm->setAttributes($aPostData);
		$oForm->validate();
		$this->assertNotEmpty($oForm->getError('phone'));
	}

	public function testResetPasswordCodeValid()
	{
		$aPostData = array(
			'smsCode' => '125665',
		);


		$oForm = new AccountResetPasswordForm('codeRequired');
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('smsCode'), print_r($oForm->getError('smsCode'), true));
	}

	public function testResetPasswordCodeNoValid()
	{
		$aPostData = array(
			'sms_code' => '',
		);


		$oForm = new AccountResetPasswordForm('codeRequired');
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('sms_code'));
	}

	public function testAddCardFormNoValid()
	{
		$aPostData = array(
			'sCardPan'   => '',
			'iCardType'  => '',
			'sCardMonth' => '',
			'sCardYear'  => '',
			'sCardCvc'   => '',
		);


		$oForm = new AddCardForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('sCardPan'));
		$this->assertNotEmpty($oForm->getError('iCardType'));
		$this->assertNotEmpty($oForm->getError('sCardMonth'));
		$this->assertNotEmpty($oForm->getError('sCardYear'));
		$this->assertNotEmpty($oForm->getError('sCardCvc'));
	}

	/**
	 * @dataProvider validMastercardCardDataProvider
	 */

	public function testAddMastercardFormValid($sCardPan, $sCardMonth, $sCardYear, $sCardCvc)
	{
		$aPostData = array(
			'sCardPan'   => $sCardPan,
			'iCardType'  => 1,
			'sCardMonth' => $sCardMonth,
			'sCardYear'  => $sCardYear,
			'sCardCvc'   => $sCardCvc,
		);

		$oForm = new AddCardForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('sCardPan'), print_r($oForm->getError('sCardPan'), true));
		$this->assertEmpty($oForm->getError('iCardType'), print_r($oForm->getError('iCardType'), true));
		$this->assertEmpty($oForm->getError('sCardMonth'), print_r($oForm->getError('sCardMonth'), true));
		$this->assertEmpty($oForm->getError('sCardYear'), print_r($oForm->getError('sCardYear'), true));
		$this->assertEmpty($oForm->getError('sCardCvc'), print_r($oForm->getError('sCardCvc'), true));

	}

	/**
	 * @dataProvider validMaestroCardDataProvider
	 */

	public function testAddMaestroFormValid($sCardPan, $sCardMonth, $sCardYear, $sCardCvc)
	{
		$aPostData = array(
			'sCardPan'   => $sCardPan,
			'iCardType'  => 2,
			'sCardMonth' => $sCardMonth,
			'sCardYear'  => $sCardYear,
			'sCardCvc'   => $sCardCvc,
		);

		$oForm = new AddCardForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('sCardPan'), print_r($oForm->getError('sCardPan'), true));
		$this->assertEmpty($oForm->getError('iCardType'), print_r($oForm->getError('iCardType'), true));
		$this->assertEmpty($oForm->getError('sCardMonth'), print_r($oForm->getError('sCardMonth'), true));
		$this->assertEmpty($oForm->getError('sCardYear'), print_r($oForm->getError('sCardYear'), true));
		$this->assertEmpty($oForm->getError('sCardCvc'), print_r($oForm->getError('sCardCvc'), true));

	}

	/**
	 * @dataProvider fieldsPassportFormForCheckSuccessProvider
	 */
	public
	function  testCheckPassportFieldsOnSuccess($field, $strRowValue, $strCleanValue)
	{
		$oForm = new ChangePassportDataForm();

		$oForm->$field = $strRowValue;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertTrue(!isset($aErrors[$field]));
		$this->assertEquals($strCleanValue, $oForm->$field);
	}

	/**
	 * @dataProvider fieldsPassportFormForCheckErrorProvider
	 */
	public
	function  testCheckPassportFieldsOnError($field, $value, $method)
	{
		$oForm = new ChangePassportDataForm();

		$oForm->$field = $value;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->$method($aErrors[$field]);
	}

	/**
	 * @return array
	 */

	public static function fieldsPassportFormForCheckSuccessProvider()
	{
		return array(
			array(
				'field'         => 'last_name',
				'strRowValue'   => ' иванов  ',
				'strCleanValue' => 'Иванов',
			),
			array(
				'field'         => 'last_name',
				'strRowValue'   => ' пеТров -  водКИН ',
				'strCleanValue' => 'Петров-Водкин',
			),
			array(
				'field'         => 'last_name',
				'strRowValue'   => ' простО ГраЦИоти  --- райСкая   ',
				'strCleanValue' => 'Просто Грациоти-Райская',
			),

			array(
				'field'         => 'last_name',
				'strRowValue'   => ' иВ Сен    лОРАН  ',
				'strCleanValue' => 'Ив Сен Лоран',
			),

			array(
				'field'         => 'first_name',
				'strRowValue'   => 'ирина',
				'strCleanValue' => 'Ирина',
			),
			array(
				'field'         => 'first_name',
				'strRowValue'   => ' владимир ',
				'strCleanValue' => 'Владимир',
			),

			array(
				'field'         => 'third_name',
				'strRowValue'   => ' ивановна ',
				'strCleanValue' => 'Ивановна',
			),

			array(
				'field'         => 'third_name',
				'strRowValue'   => ' сергеевич ',
				'strCleanValue' => 'Сергеевич',
			),

			array(
				'field'         => 'passport_series',
				'strRowValue'   => '2345',
				'strCleanValue' => '2345',
			),

			array(
				'field'         => 'passport_number',
				'strRowValue'   => '234985',
				'strCleanValue' => '234985',
			),

			array(
				'field'         => 'old_passport_series',
				'strRowValue'   => '7431',
				'strCleanValue' => '7431',
			),

			array(
				'field'         => 'old_passport_number',
				'strRowValue'   => '958366',
				'strCleanValue' => '958366',
			),
		);
	}

	/**
	 * @return array
	 */
	public
	static function fieldsPassportFormForCheckErrorProvider()
	{
		return array(
			array(
				'field'  => 'last_name',
				'value'  => 'Petrov-Водkin1',
				'method' => 'assertNotEmpty',
			),
			array(
				'field'  => 'last_name',
				'value'  => 'Петров Воdkin',
				'method' => 'assertNotEmpty',
			),
			array(
				'field'  => 'last_name',
				'value'  => '  123G46-Вод89кин  ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'last_name',
				'value'  => '7778555 88966 667  ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'first_name',
				'value'  => 'Helen',
				'method' => 'assertNotEmpty',
			),
			array(
				'field'  => 'first_name',
				'value'  => 'Анna Mария',
				'method' => 'assertNotEmpty',
			),
			array(
				'field'  => 'first_name',
				'value'  => '  123G4 6&82!!!) ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'first_name',
				'value'  => '7778555 88966 667  ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'third_name',
				'value'  => '4564 5654 32234',
				'method' => 'assertNotEmpty',
			),
			array(
				'field'  => 'third_name',
				'value'  => 'dfgdf  **#$#$ 2454',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'third_name',
				'value'  => 'Fksdgflgk',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_series',
				'value'  => '      ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_series',
				'value'  => ' 7',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_series',
				'value'  => 'ук87',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_series',
				'value'  => '---777щщз',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_number',
				'value'  => '      ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_number',
				'value'  => '   1  ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_number',
				'value'  => 'ук8754',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'passport_number',
				'value'  => '---777щщз',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_series',
				'value'  => '      ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_series',
				'value'  => ' 7',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_series',
				'value'  => 'ук87',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_series',
				'value'  => '---777щщз',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_number',
				'value'  => '      ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_number',
				'value'  => '   1  ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_number',
				'value'  => 'ук8754',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'old_passport_number',
				'value'  => '---777щщз',
				'method' => 'assertNotEmpty',
			),


		);
	}

	/**
	 * @return array
	 */

	public static function validPhoneProvider()
	{
		return array(
			array('phone' => '9' . substr((rand(1000000000, 1999999999)), 1))
		);
	}

	/**
	 * @return array
	 */

	public static function noValidPhoneProvider()
	{
		return array(
			array('phone' => rand(0, 8) . substr((rand(1000000000, 1999999999)), 1)),
			array('phone' => substr((rand(00, 1999999999)), 1)),
			array('phone' => 'dslklskdjg'),
			array('phone' => 'дпжплврпдп'),
			array('phone' => 'df897dfg79'),
			array('phone' => 'вапап35345'),
		);
	}

	/**
	 * @return array
	 */

	public static function validMastercardCardDataProvider()
	{
		$aYears = Dictionaries::getYears();
		$aMonths = Dictionaries::$aMonthsDigital;

		return array(
			array(
				'sCardPan'   => substr((rand(15000000000000000, 15599999999999999)), 1),
				'sCardMonth' => array_rand($aMonths, 1),
				'sCardYear'  => array_rand($aYears, 1),
				'sCardCvc'   => substr((rand(1000, 1999)), 1),
			)
		);
	}

	/**
	 * @return array
	 */

	public static function validMaestroCardDataProvider()
	{
		$aYears = Dictionaries::getYears();
		$aMonths = Dictionaries::$aMonthsDigital;

		$sCardPan = rand(16000000000000000, 16999999999999999);

		return array(
			array(
				'sCardPan'   => substr($sCardPan, 1),
				'sCardMonth' => array_rand($aMonths, 1),
				'sCardYear'  => array_rand($aYears, 1),
				'sCardCvc'   => substr((rand(1000, 1999)), 1),
			)
		);
	}
}