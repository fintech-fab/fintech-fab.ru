<?php

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


	public function testValidateJobFields()
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

	public function testSex()
	{
		$oForm = new ClientFullForm2();
		$oForm->sex = 5;
		$oForm->validate(array('sex'));
		$aErrors = $oForm->getErrors();
		$this->assertNotEmpty($aErrors['sex']);
		$this->assertEquals('Укажите пол', $aErrors['sex'][0]);
	}

	public function t1estJobIncomeAdd()
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
	public function  testCheckFieldsOnError($field, $value, $method)
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
	public function  testCheckFieldsOnSuccess($field, $strRowValue, $strCleanValue)
	{
		$oForm = new ClientFullForm2();

		$oForm->$field = $strRowValue;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertTrue(!isset($aErrors[$field]));
		$this->assertEquals($strCleanValue, $oForm->$field);
	}


	public static function fieldsFormForCheckErrorProvider()
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
}