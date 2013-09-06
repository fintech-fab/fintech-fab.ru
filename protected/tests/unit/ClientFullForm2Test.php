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

	public function testClientPassword()
	{

		$aPostData = array(
			'password'        => '$*!%()*@#&*(^*(@#^()*)@3498724ksjdgflhsdgfjsdgf',
			'password_repeat' => '$*!%()*@#&*(^*(@#^()*)@3498724ksjdgflhsdgfjsdgf',
		);


		$oForm = new ClientFullForm2();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('password'), print_r($oForm->getError('password'), true));

		$aPostData = array(
			'password'        => '34536546kjljflkdfjgабвгд',
			'password_repeat' => '34536546kjljflkdfjgабвгд',
		);

		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('password'));


	}

	public function testValidateFormEmail()
	{
		$oForm = new ClientFullForm2();

		$oForm->email = 'asdasdasd';
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertNotEmpty($aErrors['email']);

		$oForm->email = 'billgates@microsoft.com';
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertTrue(!isset($aErrors['email']));

	}

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
			'address_reg_as_res'  => '6',
		);

		$aPostData[] = array(
			'address_reg_region'  => 0,
			'address_reg_city'    => 'dfgfg',
			'address_reg_address' => 'dfgdfg',
			'address_res_region'  => 0,
			'address_res_city'    => 'dfgdfg',
			'address_res_address' => 'dfgdfg',
			'address_reg_as_res'  => 'sdfdfg',
		);

		$aPostData[] = array(
			'address_reg_region'  => 'dfgdfg',
			'address_reg_city'    => 'dfgfg',
			'address_reg_address' => 'dfgdfg',
			'address_res_region'  => 'dfgg',
			'address_res_city'    => 'dfgdfg',
			'address_res_address' => 'dfgdfg',
			'address_reg_as_res'  => 'dfgfg',
		);

		foreach ($aPostData as $aPost) {
			$oForm->setAttributes($aPost);
			$oForm->validate();
			$aErrors = $oForm->getErrors();
			$this->assertNotEmpty($aErrors['address_reg_region']);
			$this->assertNotEmpty($aErrors['address_reg_city']);
			$this->assertNotEmpty($aErrors['address_reg_address']);
			$this->assertNotEmpty($aErrors['address_res_region']);
			$this->assertNotEmpty($aErrors['address_res_city']);
			$this->assertNotEmpty($aErrors['address_res_address']);
			//$this->assertNotEmpty($aErrors['address_reg_as_res']);
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
			$this->assertEmpty($aErrors['address_reg_region'], print_r($oForm->getError('address_reg_region'), true));
			$this->assertEmpty($aErrors['address_reg_city'], print_r($oForm->getError('address_reg_city'), true));
			$this->assertEmpty($aErrors['address_reg_address'], print_r($oForm->getError('address_reg_address'), true));
			$this->assertNotEmpty($aErrors['address_res_region']);
			$this->assertNotEmpty($aErrors['address_res_city']);
			$this->assertNotEmpty($aErrors['address_res_address']);
			$this->assertEmpty($aErrors['address_reg_as_res'], print_r($oForm->getError('address_reg_as_res'), true));
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
			$this->assertEmpty($aErrors['address_reg_region'], print_r($oForm->getError('address_reg_region'), true));
			$this->assertEmpty($aErrors['address_reg_city'], print_r($oForm->getError('address_reg_city'), true));
			$this->assertEmpty($aErrors['address_reg_address'], print_r($oForm->getError('address_reg_address'), true));
			$this->assertEmpty($aErrors['address_res_region'], print_r($oForm->getError('address_res_region'), true));
			$this->assertEmpty($aErrors['address_res_city'], print_r($oForm->getError('address_res_city'), true));
			$this->assertEmpty($aErrors['address_res_address'], print_r($oForm->getError('address_res_address'), true));
			$this->assertEmpty($aErrors['address_reg_as_res'], print_r($oForm->getError('address_reg_as_res'), true));
		}


		/*
				$oForm->email = 'billgates@microsoft.com';
				$oForm->validate();
				$aErrors = $oForm->getErrors();
				$this->assertTrue(!isset($aErrors['email']));*/

	}


	public
	function testValidateJobFields()
	{
		$oForm = new ClientFullForm2();

		$validate_fields = array(
			'job_company',
			'job_position',
			'job_phone',
			'job_monthly_income',
			'job_monthly_outcome',
			'job_time',
		);

		$oForm->job_company = 'ООО Красный химик ';
		$oForm->job_position = 'программист';
		$oForm->job_phone = '986 878 76 76 ';
		$oForm->job_monthly_income = 6;
		$oForm->job_monthly_outcome = 3;
		$oForm->job_time = 2;
		$oForm->validate();
		$aErrors = $oForm->getErrors();

		foreach ($validate_fields as $validate_field) {
			$this->assertEmpty(
				@$aErrors[$validate_field],
				$validate_field . '  ' . $oForm->$validate_field
			);
		}
	}

	public
	function testSex()
	{
		$oForm = new ClientFullForm2();
		$oForm->sex = 5;
		$oForm->validate(array('sex'));
		$aErrors = $oForm->getErrors();
		$this->assertNotEmpty($aErrors['sex']);
		$this->assertEquals('Укажите пол', $aErrors['sex'][0]);
	}

	public
	function t1estJobIncomeAdd()
	{
		$oForm = new ClientFullForm2();
		$oForm->job_income_add = 56;
		$oForm->validate(array('job_income_add'));
		$aErrors = $oForm->getErrors();
		$this->assertNotEmpty($aErrors['job_income_add']);
		$this->assertEquals('Выберите значение поля из списка', $aErrors['job_income_add'][0]);
	}

	/**
	 * @dataProvider fieldsFormForCheckErrorProvider
	 */
	public
	function  testCheckFieldsOnError($field, $value, $method)
	{
		$oForm = new ClientFullForm2();

		$oForm->$field = $value;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->$method($aErrors[$field]);
	}

	/**
	 * @dataProvider fieldsFormForCheckSuccessProvider
	 */
	public
	function  testCheckFieldsOnSuccess($field, $strRowValue, $strCleanValue)
	{
		$oForm = new ClientFullForm2();

		$oForm->$field = $strRowValue;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertTrue(!isset($aErrors[$field]));
		$this->assertEquals($strCleanValue, $oForm->$field);
	}

	/**
	 * @return array
	 */
	public
	static function fieldsFormForCheckErrorProvider()
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
				'field'  => 'phone',
				'value'  => 'ghjjgfgjj hjhh',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'phone',
				'value'  => '-=+#$% !`^<>!!!',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'phone',
				'value'  => 'bn345jkk876h754jk',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'numeric_code',
				'value'  => 'bn345jkk876h754jk',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'numeric_code',
				'value'  => '         ',
				'method' => 'assertNotEmpty',
			),

			array(
				'field'  => 'numeric_code',
				'value'  => '33',
				'method' => 'assertNotEmpty',
			),

		);
	}

	/**
	 * @return array
	 */

	public static function fieldsFormForCheckSuccessProvider()
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
				'field'         => 'phone',
				'strRowValue'   => '9349789895',
				'strCleanValue' => '9349789895',
			),
			array(
				'field'         => 'phone',
				'strRowValue'   => 'jkkl 93 49kkl789--8/&9kl  l5gfd',
				'strCleanValue' => '9349789895',
			),
			array(
				'field'         => 'numeric_code',
				'strRowValue'   => '6789',
				'strCleanValue' => '6789',
			),
			array(
				'field'         => 'numeric_code',
				'strRowValue'   => '89809080',
				'strCleanValue' => '89809080',
			),
			array(
				'field'         => 'numeric_code',
				'strRowValue'   => '    89809080',
				'strCleanValue' => '89809080',
			),
			array(
				'field'         => 'numeric_code',
				'strRowValue'   => ' 898   09 080',
				'strCleanValue' => '89809080',
			),

		);
	}

	/**
	 * Проверка обязательности ФИО и телефона друга, если рабочий телефон одинаковый с мобильным
	 */
	public function testFriendsOnJobPhone()
	{


		$sPhone = '+7 926 266 26 26';
		$sJobPhone = $sPhone;
		$sFriendsPhone = '';
		$sFriendsFio = '';

		$aPostData = array(
			'phone'         => $sPhone,
			'job_phone'     => $sJobPhone,
			'friends_phone' => $sFriendsPhone,
			'friends_fio'   => $sFriendsFio
		);

		$oForm = new ClientFullForm2();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('friends_phone'));
		$this->assertNotEmpty($oForm->getError('friends_fio'));

		$sJobPhone = '9151001010';

		$aPostData = array(
			'phone'         => $sPhone,
			'job_phone'     => $sJobPhone,
			'friends_phone' => $sFriendsPhone,
			'friends_fio'   => $sFriendsFio
		);

		$oForm = new ClientFullForm2();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertEmpty($oForm->getError('friends_phone'), print_r($oForm->getError('friends_phone'), true));
		$this->assertEmpty($oForm->getError('friends_fio'), print_r($oForm->getError('friends_fio'), true));
	}

	public function testCheckPhonesNoValid()
	{

		$sPhone = '9' . substr((rand(1000000000, 1999999999)), 1);
		$sJobPhone = '9' . substr((rand(1000000000, 1999999999)), 1);
		$sRelOnePhone = $sPhone;
		$sFriendsPhone = $sJobPhone;

		$aPostData = array(
			'phone'               => $sPhone,
			'job_phone'           => $sJobPhone,
			'relatives_one_phone' => $sRelOnePhone,
			'friends_phone'       => $sFriendsPhone
		);


		$oForm = new ClientFullForm2();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('phone'));
		$this->assertNotEmpty($oForm->getError('job_phone'));
		$this->assertNotEmpty($oForm->getError('friends_phone'));
		$this->assertNotEmpty($oForm->getError('relatives_one_phone'));

		$sPhone = '9' . substr((rand(1000000000, 1999999999)), 1);
		$sJobPhone = $sPhone;
		$sRelOnePhone = $sPhone;
		$sFriendsPhone = $sJobPhone;

		$aPostData = array(
			'phone'               => $sPhone,
			'job_phone'           => $sJobPhone,
			'relatives_one_phone' => $sRelOnePhone,
			'friends_phone'       => $sFriendsPhone
		);


		$oForm = new ClientFullForm2();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('phone'));
		$this->assertNotEmpty($oForm->getError('job_phone'));
		$this->assertNotEmpty($oForm->getError('friends_phone'));
		$this->assertNotEmpty($oForm->getError('relatives_one_phone'));
	}

}