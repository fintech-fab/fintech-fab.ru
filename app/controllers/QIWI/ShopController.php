<?php

use App\Controllers\BaseController;

class ShopController extends BaseController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$input = Input::all();


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
		dd($httpResponse);

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
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}