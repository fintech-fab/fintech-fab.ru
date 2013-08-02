<?php
/**
 * Created by JetBrains PhpStorm.
 * User: m.novikov
 * Date: 02.08.13
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */

class ExampleTest extends CTestCase {

	public function testExample()
	{

		$aPostData = array(
			'phone' => '9104775209',
			'email' => '9104775209@@example.com',
		);


		$oForm = new ClientPersonalDataForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		//$this->assertEmpty($oForm->getErrors(), print_r($oForm->getErrors(),true));
		$this->assertNotEmpty($oForm->getErrors()['email']);



		$this->assertEquals(1,1);
	}

}