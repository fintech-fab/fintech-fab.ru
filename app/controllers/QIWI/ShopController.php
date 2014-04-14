<?php

use App\Controllers\BaseController;
use FintechFab\Components\WorkWithInput;
use FintechFab\Models\Qiwi\ShopBill;

class ShopController extends BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$data = array(
			'tel'     => '123123123123',
			'sum'     => '123',
			'comment' => 'qwerty',
			'userID'  => '1',
		);
		$validator = Validator::make($data, WorkWithInput::rulesForInputBuy(), WorkWithInput::messagesForErrors());
		$userMessages = $validator->messages();

		if ($userMessages->has('sum') || $userMessages->has('tel')) {
			$sumError = $userMessages->first('sum');
			$telError = $userMessages->first('tel');
			$result['errors'] = array($sumError, $telError);

			return $result;
		}

		$lifetime = date('Y-m-d\TH:i:s', time() + 3600 * 24 * 3);
		$user = 'tel:' . $data['tel'];

		$bill = new ShopBill;
		$bill->user_id = $data['userID'];
		$bill->user = $user;
		$bill->amount = $data['sum'];
		$bill->ccy = 'RUB';
		$bill->comment = $data['comment'];
		$bill->lifetime = $lifetime;
		$bill->pay_source = 'qw';
		$bill->prv_name = 'FintechFab';
		$bill->save();

		$url = 'http://fintech-fab.dev:8080/api/v2/prv/2042/bills/' . $bill['id'];


		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8"
		);

		$query = http_build_query(array(

			"user"       => $bill['user'],
			"amount"     => $bill['amount'],
			"ccy"        => $bill['ccy'],
			"comment"    => $bill['comment'],
			"lifetime"   => $bill['lifetime'],
			"pay_source" => $bill['qw'],
			"prv_name"   => $bill['prv_name'],
		));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'test:pass');

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);


		$httpResponse = curl_exec($ch);
		if (!$httpResponse) {
			// Описание ошибки, к примеру
			$res = curl_error($ch) . '(' . curl_errno($ch) . ')';
			echo $res;


		}
		echo "<pre>\n";
		echo htmlentities($httpResponse);
		echo "</pre>\n";
		die();
		$output = curl_exec($ch);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$validator = Validator::make($data, WorkWithInput::rulesForInputBuy(), WorkWithInput::messagesForErrors());
		$userMessages = $validator->messages();

		if ($userMessages->has('sum') || $userMessages->has('tel')) {
			$sumError = $userMessages->first('sum');
			$telError = $userMessages->first('tel');
			$result['errors'] = array($sumError, $telError);

			return $result;
		}

		$lifetime = date('Y-m-d\TH:i:s', time() + 3600 * 24 * 3);
		$user = 'tel:' . $data['tel'];

		$bill = new ShopBill;
		$bill->user_id = $data['userID'];
		$bill->user = $user;
		$bill->amount = $data['sum'];
		$bill->ccy = 'RUB';
		$bill->comment = $data['comment'];
		$bill->lifetime = $lifetime;
		$bill->pay_source = 'qw';
		$bill->prv_name = 'FintechFab';
		$bill->save();

		$url = 'http://fintech-fab.dev:8080/api/v2/prv/2042/bills/' . $bill['id'];


		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8"
		);

		$query = http_build_query(array(
			"user"       => $bill['user'],
			"amount"     => $bill['amount'],
			"ccy"        => $bill['ccy'],
			"comment"    => $bill['comment'],
			"lifetime"   => $bill['lifetime'],
			"pay_source" => $bill['qw'],
			"prv_name"   => $bill['prv_name'],
		));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'test:pass');

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));

		$httpResponse = curl_exec($ch);
		if (!$httpResponse) {
			// Описание ошибки, к примеру
			$res = curl_error($ch) . '(' . curl_errno($ch) . ')';
			dd($res);

		}

		echo "<pre>\n";
		echo htmlentities($httpResponse);
		echo "</pre>\n";
		die();
		$output = curl_exec($ch);


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}