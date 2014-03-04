<?php

/**
 * @var array $userInfo
 */


use FintechFab\Models\UserVk;

$client_id = Config::get('vk.ID_vk'); // ID приложения
$client_secret = Config::get('vk.key_vk'); // Защищённый ключ
$redirect_uri = Config::get('vk.url'); // Адрес сайта

if (isset($_GET['code'])) {
$result = false;
$params = array(
	'client_id'     => $client_id,
	'client_secret' => $client_secret,
	'code'          => $_GET['code'],
	'redirect_uri'  => $redirect_uri
);

$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

if (isset($token['access_token'])) {
	$params = array(
		'uids'         => $token['user_id'],
		'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
		'access_token' => $token['access_token']
	);

	$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
	if (isset($userInfo['response'][0]['uid'])) {
		$userInfo = $userInfo['response'][0];
		$result = true;
	}
}
?>
<div class="row text-center">
	<?php
	if ($result) {
		$id_vk = $userInfo['uid'];
		$first_name = $userInfo['first_name'];
		$last_name = $userInfo['last_name'];
		$bdate = $userInfo['bdate'];
		echo "Социальный ID пользователя: " . $id_vk . '<br />';
		echo "Имя пользователя: " . $first_name . '<br />';
		echo "Фамилия пользователя: " . $last_name . '<br />';
		echo "День Рождения: " . $bdate . '<br />';
		echo "<br />";

		//dd($id_vk);

		/*
		$component = new UserSocialComponent;
		$component->doEntrySocialNetwork();

		if($component->getError()){

		}else{

		}

		if($component->user){

		}
		if($component->socual_network){

		}

		$sn = Input::get('sn');

		if($sn == 'vk'){
			$sn_comp = new VkComponent();
		}

		$sn_comp->parseData();
		$sn_comp->createUser();
		$sn_comp->createUserNetwork();

		*/


		$user = UserVk::firstOrNew(array(
			'id_vk' => $id_vk,
		));

		$user->setAttribute('id_vk', $id_vk);
		$user->setAttribute('first_name', $first_name);
		$user->setAttribute('last_name', $last_name);
		$user->save();

	}
	}
	?>
</div>