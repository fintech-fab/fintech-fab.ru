<?php
use FintechFab\Models\User;

$user = User::find(Auth::user()->id);
$userSocial = User::find($user['id'])->SocialNetworks()->first()->toArray();
/*echo $userSocial['photo'];
echo'<pre>';
print_r($userSocial);
echo'</pre>';
die();*/

?>
<div class="jumbotron ">
	<h2 class="text-center">Профиль</h2>    <br>

	<div class="row">
		<img src="<?= $userSocial['photo'] ?>" class="img-rounded col-md-offset-1 col-md-3">

		<p class="col-md-offset-2 col-md-2">Имя:</p>

		<p class="col-md-2"><?= $user['first_name'] ?></p>        <br>

		<p class="col-md-offset-2 col-md-2">Фамилия:</p>

		<p class="col-md-2"><?= $user['last_name'] ?></p>        <br>

		<p class="col-md-offset-2 col-md-2">E-mail:</p>

		<p class="col-md-2"><?= $user['email'] ?></p>        <br>
	</div>
	<h2 class="text-center">Подключить соцсеть</h2>
</div>
