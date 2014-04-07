<?php

use FintechFab\Components\Soap\VanguardOrder;

class SoapTest extends TestCase
{

	public function testSoapClientExample()
	{

		try {

			$oSoap = new SoapClient(null, array(
				'location' => 'http://fintech-fab.dev',
				'uri'      => 'soap',
			));

			/**
			 * @var VanguardOrder $oSoap
			 */
			$oSoap->CreateOrder();

		} catch (Exception $oException) {
			//dd($oException);
		}

	}

	public function testSoapClientCbr()
	{

		$oSoap = new SoapClient('http://www.cbr.ru/dailyinfowebserv/dailyinfo.asmx?WSDL');

		/** @noinspection PhpUndefinedMethodInspection */
		$oResponse = $oSoap->Saldo(array(
			'fromDate' => date('Y-m-d', strtotime('-30 day')),
			'ToDate'   => date('Y-m-d'),
		));


		/**
		 * @var SimpleXMLElement $oXml
		 * @var SimpleXMLElement $o
		 */
		$oXml = simplexml_load_string($oResponse->SaldoResult->any);

		$aResult = array();

		foreach ($oXml->Saldo->So as $o) {
			$aResult[date('Y-m-d', strtotime((string)$o->Dt))] = (string)$o->DEADLINEBS;
		}

	}

}