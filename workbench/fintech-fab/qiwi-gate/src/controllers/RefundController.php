<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use Response;

class RefundController extends Controller
{

	/**
	 * Возврат средств
	 *
	 * @param int    $provider_id
	 * @param string $bill_id
	 * @param int    $refund_id
	 *
	 * @internal param int $id
	 *
	 * @return Response
	 */
	public function update($provider_id, $bill_id, $refund_id)
	{

		dd('возврат средств: ' . $provider_id . ' / ' . $bill_id . '/' . $refund_id);

	}

	/**
	 * Проверка статуса возврата
	 *
	 * @param int    $provider_id
	 * @param string $bill_id
	 * @param int    $refund_id
	 *
	 * @internal param int $id
	 *
	 * @return Response
	 */
	public function show($provider_id, $bill_id, $refund_id)
	{

		dd('проверка статуса возврата ' . $provider_id . ' / ' . $bill_id . '/' . $refund_id);

	}

}