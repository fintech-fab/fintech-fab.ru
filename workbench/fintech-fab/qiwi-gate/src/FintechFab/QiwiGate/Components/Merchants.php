<?php
namespace FintechFab\QiwiGate\Components;

use Config;
use FintechFab\QiwiGate\Models\Merchant;

class Merchants
{

	/**
	 * @param Merchant $merchant
	 * @param array    $data
	 *
	 * @return Merchant
	 */
	public static function NewMerchant($merchant, $data)
	{

		$merchant->id = Config::get('ff-qiwi-gate::user_id');
		$merchant->username = $data['username'];
		$merchant->callback_url = $data['callback'];
		$merchant->password = $data['password'];
		$merchant->save();

		return $merchant;

	}

} 