<?php
namespace FintechFab\Components;

use Config;
use Input;
use Redirect;

class GetSocialUser
{
	public static function vk()
	{
		$result = false;
		$params = array(
			'client_id'     => Config::get('social.vk.ID'),
			'client_secret' => Config::get('social.vk.key'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.vk.redirect_url')
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
			GetSocialUser::resultError();
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
			'client_id'     => Config::get('social.fb.ID'),
			'client_secret' => Config::get('social.fb.key'),
			'code'          => Input::get('code'),
			'redirect_uri'  => Config::get('social.fb.redirect_url')
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
			GetSocialUser::resultError();
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