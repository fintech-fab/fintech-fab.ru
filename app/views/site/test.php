<?php
$client_id = Config::get('vk.ID_vk'); // ID приложения
$client_secret = Config::get('vk.key_vk'); // Защищённый ключ
$redirect_uri = Config::get('vk.url'); // Адрес сайта

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