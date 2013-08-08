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
			'birthday' => '22.12.2009',
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
			'birthday' => '31.11.2009',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('birthday'));

	}

	public function testCheckPassportValid()
	{

		$aPostData = array(
			'passport_date' => '29.02.2008',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('passport_date'), print_r($oForm->getError('passport_date'),true));
		//$this->assertNotEmpty($oForm->getErrors()['email']);

	}

	public function testCheckPassportNonValid()
	{

		$aPostData = array(
			'passport_date' => '29.02.2005',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('passport_date'));
		//$this->assertNotEmpty($oForm->getErrors()['email']);

	}

}