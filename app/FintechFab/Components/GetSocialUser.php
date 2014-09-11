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

		$paramsQuery = http_build_query($params);
		$url = 'https://oauth.vk.com/access_token' . '?' . $paramsQuery;
		$content = file_get_contents($url);
		$json = json_decode($content);

		if (isset($json['access_token'])) {
			$params = array(
				'uids'         => $json['user_id'],
				'fields'       => 'uid,first_name,last_name,bdate,screen_name,photo_big',
				'access_token' => $json['access_token']
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

	public static function gp()
	{
		$params = array(
			'code'          => Input::get('code'),
			'grant_type'    => 'authorization_code',
			'client_id'     => Config::get('social.gp.ID'),
			'client_secret' => Config::get('social.gp.key'),
			'redirect_uri'  => Config::get('social.gp.redirect_url'),
		);

		$tokenInfo = null;

		$ch = curl_init("https://accounts.google.com/o/oauth2/token");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		$error = curl_error($ch);

		if (!$result || $error) {
			return false;
		}

		return false;

	}

	public static function resultError()
	{
		return Redirect::to('registration')
			->with('userMessage', "Что-то пошло не так :-(")
			->with('userMessageTitle', 'Ошибка');
	}
}