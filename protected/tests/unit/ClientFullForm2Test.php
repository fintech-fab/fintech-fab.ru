<?php
/**
 * Class ClientFullForm2Test
 * @method assertEmpty
 * @method assertNotEmpty
 * @method assertTrue
 * @method assertEquals
 *
 */

class ClientFullForm2Test extends CTestCase
{


	public function testValidateAddress()
	{
		$oForm = new ClientFullForm2();

		$aPostData = array();
		$aPostData[] = array(
			'address_reg_region'  => '',
			'address_reg_city'    => '',
			'address_reg_address' => '',
			'address_res_region'  => '',
			'address_res_city'    => '',
			'address_res_address' => '',
			'address_reg_as_res'  => '',
		);

		$aPostData[] = array(
			'address_reg_region'  => 0,
			'address_reg_city'    => '1dfgfg',
			'address_reg_address' => '2dfgdfg',
			'address_res_region'  => 0,
			'address_res_city'    => '3dfgdfg',
			'address_res_address' => '4dfgdfg',
			'address_reg_as_res'  => '',
		);

		$aPostData[] = array(
			'address_reg_region'  => 'dfgdfg',
			'address_reg_city'    => '6dfgfg',
			'address_reg_address' => '7dfgdfg',
			'address_res_region'  => 'dfgg',
			'address_res_city'    => '9dfgdfg',
			'address_res_address' => '10dfgdfg',
			'address_reg_as_res'  => '',
		);

		foreach ($aPostData as $aPost) {
			$oForm->setAttributes($aPost);
			$oForm->validate();
			$aErrors = $oForm->getErrors();
			$this->assertNotEmpty(@$aErrors['address_reg_region'], $aPost['address_reg_region']);
			$this->assertNotEmpty(@$aErrors['address_reg_city'], $aPost['address_reg_city']);
			$this->assertNotEmpty(@$aErrors['address_reg_address'], $aPost['address_reg_address']);
			$this->assertNotEmpty(@$aErrors['address_res_region'], $aPost['address_res_region']);
			$this->assertNotEmpty(@$aErrors['address_res_city'], $aPost['address_res_city']);
			$this->assertNotEmpty(@$aErrors['address_res_address'], $aPost['address_res_address']);
			//$this->assertNotEmpty($aErrors['address_reg_as_res'],$aPost['address_reg_as_res']);
		}

		$aPostData = array();
		$aPostData[] = array(
			'address_reg_region'  => 2,
			'address_reg_city'    => 'ввапрапрпп 11',
			'address_reg_address' => 'вапвапвапапрапр 22',
			'address_res_region'  => '',
			'address_res_city'    => '',
			'address_res_address' => '',
			'address_reg_as_res'  => 0,
		);

		$aPostData[] = array(
			'address_reg_region'  => 20,
			'address_reg_city'    => 'вапрапрпп',
			'address_reg_address' => 'вапвапвапапрап',
			'address_res_region'  => 'dfgdfgdf',
			'address_res_city'    => 'dfgdfg',
			'address_res_address' => 'dfgdfgdf',
			'address_reg_as_res'  => 0,
		);

		foreach ($aPostData as $aPost) {
			$oForm->setAttributes($aPost);
			$oForm->validate();
			$aErrors = $oForm->getErrors();
			$this->assertEmpty(@$aErrors['address_reg_region'], print_r($oForm->getError('address_reg_region'), true));
			$this->assertEmpty(@$aErrors['address_reg_city'], print_r($oForm->getError('address_reg_city'), true));
			$this->assertEmpty(@$aErrors['address_reg_address'], print_r($oForm->getError('address_reg_address'), true));
			$this->assertNotEmpty(@$aErrors['address_res_region']);
			$this->assertNotEmpty(@$aErrors['address_res_city']);
			$this->assertNotEmpty(@$aErrors['address_res_address']);
			$this->assertEmpty(@$aErrors['address_reg_as_res'], print_r($oForm->getError('address_reg_as_res'), true));
		}

		$aPostData = array();
		$aPostData[] = array(
			'address_reg_region'  => 2,
			'address_reg_city'    => 'ввапрапрпп 11',
			'address_reg_address' => 'вапвапвапапрапр 22',
			'address_res_region'  => '',
			'address_res_city'    => '',
			'address_res_address' => '',
			'address_reg_as_res'  => 1,
		);

		$aPostData[] = array(
			'address_reg_region'  => 20,
			'address_reg_city'    => 'вапрапрпп',
			'address_reg_address' => 'вапвапвапапрап',
			'address_res_region'  => 'dfgdfgdf',
			'address_res_city'    => 'dfgdfg',
			'address_res_address' => 'dfgdfgdf',
			'address_reg_as_res'  => 1,
		);

		foreach ($aPostData as $aPost) {
			$oForm->setAttributes($aPost);
			$oForm->validate();
			$aErrors = $oForm->getErrors();
			$this->assertEmpty(@$aErrors['address_reg_region'], print_r($oForm->getError('address_reg_region'), true));
			$this->assertEmpty(@$aErrors['address_reg_city'], print_r($oForm->getError('address_reg_city'), true));
			$this->assertEmpty(@$aErrors['address_reg_address'], print_r($oForm->getError('address_reg_address'), true));
			$this->assertEmpty(@$aErrors['address_res_region'], print_r($oForm->getError('address_res_region'), true));
			$this->assertEmpty(@$aErrors['address_res_city'], print_r($oForm->getError('address_res_city'), true));
			$this->assertEmpty(@$aErrors['address_res_address'], print_r($oForm->getError('address_res_address'), true));
			$this->assertEmpty(@$aErrors['address_reg_as_res'], print_r($oForm->getError('address_reg_as_res'), true));
		}


		/*
				$oForm->email = 'billgates@microsoft.com';
				$oForm->validate();
				$aErrors = $oForm->getErrors();
				$this->assertTrue(!isset($aErrors['email']));*/

	}


}