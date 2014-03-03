<?php
$client_id = Config::get('vk.ID_vk'); // ID приложения
$client_secret = Config::get('vk.key_vk'); // Защищённый ключ
$redirect_uri = 'http://fintech-fab.dev:8080/vk'; // Адрес сайта

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
	<?php    if ($result) {
		$id_vk = $userInfo['uid'];
		$first_name = $userInfo['first_name'];
		$last_name = $userInfo['last_name'];
		$bdate = $userInfo['bdate'];
		echo "Социальный ID пользователя: " . $id_vk . '<br />';
		echo "Имя пользователя: " . $first_name . '<br />';
		echo "Фамилия пользователя: " . $last_name . '<br />';
		echo "День Рождения: " . $bdate . '<br />';
		echo "<br />";

		$users = DB::table('users_vk')->get();
		$res = false;
		foreach ($users as $user) {
			if ($id_vk == $user->id_vk) {
				$res = true;
				break;
			}
		}
		if ($res) {
			echo "Такой пользователь уже есть в базе.";
			$res = false;
		} else {
			DB::table('users_vk')->insert(
				array(
					'id_vk'      => $id_vk,
					'first_name' => $first_name,
					'last_name'  => $last_name,
				)
			);
			echo "Пользователь внесен в базу";
		}

	}
	}
	?>
</div>