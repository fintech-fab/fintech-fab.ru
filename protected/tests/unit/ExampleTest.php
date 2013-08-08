<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.novikov
 * Date: 02.08.13
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */

class ExampleTest extends CTestCase {


	public function testCheckBirthdayValid()
	{

		$aPostData = array(
			'birthday' => '22.12.1991',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('birthday'), print_r($oForm->getError('birthday'),true));
		//$this->assertNotEmpty($oForm->getErrors()['email']);

	}

	public function testCheckBirthdayNonValid()
	{

		$aPostData = array(
			'birthday' => '30.11.2000',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('birthday'));

	}

	public function testCheckPassportValid()
	{

		$aPostData = array(
			'birthday' => '29.02.1994',
			'passport_date' => '01.03.2008',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('passport_date'), print_r($oForm->getError('passport_date'),true));


	}

	public function testCheckPassportValid2()
	{

		$aPostData = array(
			'birthday' => '10.10.1945',
			'passport_date' => '29.02.2000',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('passport_date'), print_r($oForm->getError('passport_date'),true));


	}


	public function testCheckPassportNonValid()
	{

		$aPostData = array(
			'birthday' => '10.10.1945',
			'passport_date' => '29.02.2005',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('passport_date'));


	}

	public function testCheckPassportNonValid2()
	{

		$aPostData = array(
			'birthday' => '10.10.1945',
			'passport_date' => '22.02.1990',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('passport_date'));

	}

	public function testCheckPassportNonValid3()
	{

		$aPostData = array(
			'birthday' => '08.08.2000',
			'passport_date' => '08.08.2005',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('passport_date'));

	}

	public function testCheckPassportNonValid4()
	{

		$aPostData = array(
			'birthday' => '20.07.2045',
			'passport_date' => '11.09.1910',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('passport_date'));

	}

	public function testCheckPassportValid3()
	{

		$aPostData = array(
			'birthday' => '22.02.1945',
			'passport_date' => '08.08.2013',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('passport_date'), print_r($oForm->getError('passport_date'),true));
	}


	public function ntestCheckPassportValidRand()
	{

		$randomTimestamp = mktime(0,0,0,rand(1,12),rand(1,31), rand(1900,2050));
		$sBirthday = date('d.m.Y', $randomTimestamp);
		$randomTimestamp = mktime(0,0,0,rand(1,12),rand(1,31), rand(1900,2050));
		$sPassportDate = date('d.m.Y', $randomTimestamp);

		$aPostData = array(
			'birthday' => $sBirthday,
			'passport_date' => $sPassportDate
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$sError = $oForm->getError('passport_date');
		$sMessage = $oForm->getError('passport_date');
		$sMessage .= "\nBirthday: ".$aPostData['birthday'];
		$sMessage .= "\nPassport Date: ".$aPostData['passport_date'];
		$this->assertEmpty($sError, print_r($sMessage,true));

		$this->assertNotEmpty($sError, print_r($sMessage,true));
	}
}