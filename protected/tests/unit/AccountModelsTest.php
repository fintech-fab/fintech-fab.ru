<?php
/**
 * Class ModelsTest
 * @method assertEmpty
 * @method assertNotEmpty
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
			'smsCode' => '',
		);


		$oForm = new AccountResetPasswordForm('codeRequired');
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('smsCode'));
	}

	public function testAddCardFormNoValid()
	{
		$aPostData = array(
			'sCardPan' => '',
			'sCardMonth' => '',
			'sCardYear' => '',
			'sCardCvc' => '',
		);


		$oForm = new AddCardForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('sCardPan'));
		$this->assertNotEmpty($oForm->getError('sCardMonth'));
		$this->assertNotEmpty($oForm->getError('sCardYear'));
		$this->assertNotEmpty($oForm->getError('sCardCvc'));
	}

	/**
	 * @return array
	 */

	public static function validPhoneProvider()
	{
		 return array(
			 array('phone'=>'9' . substr((rand(1000000000, 1999999999)), 1))
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
			array('phone'=>'dslklskdjg'),
			array('phone'=>'дпжплврпдп'),
			array('phone'=>'df897dfg79'),
			array('phone'=>'вапап35345'),
		);
	}

}