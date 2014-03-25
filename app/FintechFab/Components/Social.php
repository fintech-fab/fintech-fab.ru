<?php

namespace FintechFab\Components;

use Auth;
use Config;
use FintechFab\Models\SocialNetwork;
use FintechFab\Models\User;

class Social
{

	public static function linkForSocNet($socNetName)
	{
		$client_id = Config::get('social.' . $socNetName . '.ID'); // ID приложения
		$redirect_uri = Config::get('social.' . $socNetName . '.redirect_url'); // Адрес сайта
		$url = Config::get('social.' . $socNetName . '.url');
		$scope = Config::get('social.' . $socNetName . '.scope');

		$params = array(
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code',
			'scope'         => $scope
		);

		$link = $url . '?' . urldecode(http_build_query($params));

		return $link;
	}

	public static function setSocialUser($userInfo)
	{
		$userSocialNetwork = SocialNetwork::firstOrNew(array(
			'id_user_in_network' => $userInfo['id'],
		));
		if (Auth::check()) {
			$userSocialNetwork['user_id'] = Auth::user()->id;
		}

		if ($userSocialNetwork['user_id'] != null) {
			$user = User::find($userSocialNetwork['user_id']);
			if ($user['photo'] == '/img/default_user.png') { //Не придумал как избежать вложенного if`а
				$user->photo = $userInfo['photo'];
				$user->save();
			}
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