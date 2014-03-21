<?php

namespace FintechFab\Components;

use Config;
use FintechFab\Models\SocialNetwork;
use FintechFab\Models\User;

class Social
{

	public static function vk()
	{
		$client_id = Config::get('social.ID_vk'); // ID приложения
		$redirect_uri = Config::get('social.url_vk'); // Адрес сайта

		$url = 'http://oauth.vk.com/authorize';

		$params = array(
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code'
		);

		$link = $url . '?' . urldecode(http_build_query($params));

		return $link;
	}

	public static function fb()
	{
		$client_id = Config::get('social.ID_fb'); // ID приложения
		$redirect_uri = Config::get('social.url_fb'); // Адрес сайта

		$url = 'https://www.facebook.com/dialog/oauth';

		$params = array(
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code'
		);

		$link = $url . '?' . urldecode(http_build_query($params));

		return $link;
	}

	public static function setSocialUser($userInfo)
	{
		$userSocialNetwork = SocialNetwork::firstOrNew(array(
			'id_user_in_network' => $userInfo['id'],
		));

		if ($userSocialNetwork['user_id'] != null) {
			$user = User::find($userSocialNetwork['user_id']);
		} else {
			$user = new User();
			$user->first_name = $userInfo['first_name'];
			$user->last_name = $userInfo['last_name'];
			$user->photo = $userInfo['photo'];
			$user->save();
		}
		$userSocialNetwork->user_id = $user['id'];
		$userSocialNetwork->setAttribute('id_user_in_network', $userInfo['id']);
		$userSocialNetwork->setAttribute('first_name', $userInfo['first_name']);
		$userSocialNetwork->setAttribute('last_name', $userInfo['last_name']);
		$userSocialNetwork->setAttribute('link', $userInfo['link']);
		$userSocialNetwork->setAttribute('photo', $userInfo['photo']);
		$userSocialNetwork->setAttribute('social_net_name', $userInfo['social_net_name']);
		$userSocialNetwork->save();

		return $user;
	}
}