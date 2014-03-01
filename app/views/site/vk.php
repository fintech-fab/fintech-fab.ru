<?php
$client_id = 'ID'; // ID приложения
$client_secret = 'key'; // Защищённый ключ
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

		echo "Социальный ID пользователя: " . $userInfo['uid'] . '<br />';
		echo "Имя пользователя: " . $userInfo['first_name'] . '<br />';
		echo "Ссылка на профиль пользователя: " . $userInfo['screen_name'] . '<br />';
		echo "Пол пользователя: " . $userInfo['sex'] . '<br />';
		echo "День Рождения: " . $userInfo['bdate'] . '<br />';
		echo '<img src="' . $userInfo['photo_big'] . '" />';
		echo "<br />";
	}
	}
	?>
</div>