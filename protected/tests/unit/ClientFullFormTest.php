<?php

/**
 * Class ClientFullFormTest
 * @method assertEmpty
 * @method assertNotEmpty
 * @method assertTrue
 * @method assertEquals
 *
 */
class ClientFullFormTest extends \PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		YiiBase::$enableIncludePath = false;
	}

	public function testClientPassword()
	{

		$aPostData = array(
			'password'        => '$*!%()*@#&*(^*(@#^()*)@3498724ksjdAzDFgflhsdgfjsdgf,.-+\/<>}{',
			'password_repeat' => '$*!%()*@#&*(^*(@#^()*)@3498724ksjdAzDFgflhsdgfjsdgf,.-+\/<>}{',
		);


		$oForm = new ClientFullForm();
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
		$oForm = new ClientFullForm();

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
		$oForm = new ClientFullForm();

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
	}


	public
	function testValidateJobFields()
	{
		$oForm = new ClientFullForm();

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
		$oForm = new ClientFullForm();
		$oForm->sex = 5;
		$oForm->validate(array('sex'));
		$aErrors = $oForm->getErrors();
		$this->assertNotEmpty($aErrors['sex']);
		$this->assertEquals('Укажи пол', $aErrors['sex'][0]);
	}

	/**
	 * @dataProvider fieldsFormForCheckErrorProvider
	 */
	public
	function  testCheckFieldsOnError($field, $value, $method)
	{
		$oForm = new ClientFullForm();

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
		$oForm = new ClientFullForm();

		$oForm->$field = $strRowValue;
		$oForm->validate();
		$aErrors = $oForm->getErrors();
		$this->assertTrue(!isset($aErrors[$field]));
		$this->assertEquals($strCleanValue, $oForm->$field);
	}

	public function testDocumentOnSuccess()
	{
		$oForm = new ClientFullForm();

		$aTestData = array(
			//загранпаспорт
			array(
				'document'        => 1,
				'document_number' => '123456789',
			),
			array(
				'document'        => 1,
				'document_number' => '12№3456789',
			),
			array(
				'document'        => 1,
				'document_number' => '12-3456789',
			),
			array(
				'document'        => 1,
				'document_number' => '12/3456789',
			),
			array(
				'document'        => 1,
				'document_number' => '12 3456789',
			),
			//водительское удостоверение
			array(
				'document'        => 2,
				'document_number' => '12ЖД 345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12 АБ345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12 - ВГ - 345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12ДЕ345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12ЖЗ-345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12ИЙ/345678',
			),
			array(
				'document'        => 2,
				'document_number' => '12ИЙ №345678',
			),
			//военный билет
			array(
				'document'        => 4,
				'document_number' => 'АБ 1234567',
			),
			array(
				'document'        => 4,
				'document_number' => 'ВГ7654321',
			),
			array(
				'document'        => 4,
				'document_number' => 'ДЕ-1234567',
			),
			array(
				'document'        => 4,
				'document_number' => 'ЁЖ/1234567',
			),
			array(
				'document'        => 4,
				'document_number' => 'АБ№1234567',
			),
			//ИНН
			array(
				'document'        => 5,
				'document_number' => '1234 5678 9012',
			),
			array(
				'document'        => 5,
				'document_number' => '1234-5678-9012',
			),
			array(
				'document'        => 5,
				'document_number' => '1234/5678/9012',
			),
			array(
				'document'        => 5,
				'document_number' => '№1234-5678-9012',
			),
			array(
				'document'        => 5,
				'document_number' => '№1234 - 5678 - 9012',
			),
			//СНИЛС
			array(
				'document'        => 6,
				'document_number' => '123-456-789-01',
			),
			array(
				'document'        => 6,
				'document_number' => '123 456 789 01',
			),
			array(
				'document'        => 6,
				'document_number' => '123/456/789/01',
			),
			array(
				'document'        => 6,
				'document_number' => '№123-456-789-01',
			),

		);

		foreach ($aTestData as $aDocument) {
			$oForm->document = $aDocument['document'];
			$oForm->document_number = $aDocument['document_number'];
			$oForm->validate('document_number');
			$aErrors = $oForm->getErrors();
			$this->assertFalse(isset($aErrors['document_number']));
		}
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

		$oForm = new ClientFullForm();
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

		$oForm = new ClientFullForm();
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


		$oForm = new ClientFullForm();
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


		$oForm = new ClientFullForm();
		$oForm->setAttributes($aPostData);

		$oForm->validate();

		$this->assertNotEmpty($oForm->getError('phone'));
		$this->assertNotEmpty($oForm->getError('job_phone'));
		$this->assertNotEmpty($oForm->getError('friends_phone'));
		$this->assertNotEmpty($oForm->getError('relatives_one_phone'));
	}

}