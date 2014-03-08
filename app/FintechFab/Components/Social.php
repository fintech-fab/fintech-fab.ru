<?php

namespace FintechFab\Components;

use Config;

class Social
{

	public static function vk()
	{
		$client_id = Config::get('vk.ID_vk'); // ID приложения
		$redirect_uri = Config::get('vk.url'); // Адрес сайта

		$url = 'http://oauth.vk.com/authorize';

		$params = array(
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code'
		);

		$link = '<a href="' . $url . '?' . urldecode(http_build_query($params)) . '"><img src="/assets/ico/vk32.png" alt="" /></a>';

		return $link;
	}

} 