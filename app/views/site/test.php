<?php
$client_id = 'ID'; // ID приложения
$client_secret = 'key'; // Защищённый ключ
$redirect_uri = 'http://fintech-fab.dev:8080/vk'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize';

$params = array(
	'client_id'     => $client_id,
	'redirect_uri'  => $redirect_uri,
	'response_type' => 'code'
);?>
<div class="row text-center">
	<?php
	echo $link = '<p><a href="' . $url . '?' . urldecode(http_build_query($params)) . '">Аутентификация через ВКонтакте</a></p>';
	?>
</div>