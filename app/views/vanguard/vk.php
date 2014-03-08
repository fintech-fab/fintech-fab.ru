<?php
/**
 * @var integer $id_vk
 * @var string  $first_name
 * @var string  $last_name
 * @var integer $bdate
 */
$id_vk = Session::get('id_vk');
$first_name = Session::get('first_name');
$last_name = Session::get('last_name');
$bdate = Session::get('bdate');
?>
<div class="row text-center">

	<p>Социальный ID пользователя:  <?= $id_vk ?></p>

	<p>Имя пользователя: <?= $first_name ?></p>

	<p>Фамилия пользователя: <?= $last_name ?></p>

	<p>День Рождения: <?= $bdate ?></p>
</div>