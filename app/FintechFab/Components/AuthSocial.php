<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.14
 * Time: 15:08
 */

namespace FintechFab\Components;


use Config;
use Input;
use Redirect;

class AuthSocial
{
	public static function vk()
	{
		$result = false;
		$params = array(
			'client_id'     => Config::get('social.ID_vk'),
			'client_secret' => Config::get('social.key_vk'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.url_vk')
		);

		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?'
			. urldecode(http_build_query($params))), true);

		if (isset($token['access_token'])) {
			$params = array(
				'uids'         => $token['user_id'],
				'fields'       => 'uid,first_name,last_name,bdate,screen_name,photo_big',
				'access_token' => $token['access_token']
			);

			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?'
				. urldecode(http_build_query($params))), true);

			if (isset($userInfo['response'][0]['uid'])) {
				$userInfo = $userInfo['response'][0];
				$result = true;
			}
		}
		if (!$result) {
			AuthSocial::resultError();
		}
		$userInfo['social_net_name'] = 'vk';
		$userInfo['id'] = $userInfo['uid'];
		$userInfo['photo'] = $userInfo['photo_big'];
		$userInfo['link'] = 'https://vk.com/' . $userInfo['screen_name'];

		return $userInfo;
	}

	public static function fb()
	{
		$params = array(
			'client_id'     => Config::get('social.ID_fb'),
			'client_secret' => Config::get('social.key_fb'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.url_fb')
		);

		$tokenInfo = null;
		parse_str(file_get_contents('https://graph.facebook.com/oauth/access_token' . '?'
			. http_build_query($params)), $tokenInfo);

		$params = array(
			'fields'       => 'id,first_name,last_name,link,birthday,picture.type(large)',
			'access_token' => $tokenInfo['access_token']
		);
		$userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?'
			. urldecode(http_build_query($params))), true);

		if (!isset($userInfo['id'])) {
			AuthSocial::resultError();
		}
		$userInfo['social_net_name'] = 'fb';
		$userInfo['photo'] = $userInfo['picture']['data']['url'];

		return $userInfo;
	}

	public static function resultError()
	{
		return Redirect::to('register')
			->with('userMessage', "Что-то не так, попробуйте ещё раз")
			->with('userMessageTitle', 'Ошибка');
	}
} 