<?php
use FintechFab\Models\User;

$user = User::find(Auth::user()->id)->first()->toArray();
$userSocial = User::find($user['id'])->SocialNetworks()->get()->toArray();

/*echo'<pre>';
print_r($userSocial);
print_r($user);
echo'</pre>';
die();*/

?>
<div class="jumbotron ">
	<h2 class="text-center">Профиль</h2>    <br>

	<div class="row">
		<img src="<?= $user['photo'] ?>" class="img-rounded col-md-offset-1 col-md-3">

		<p class="col-md-offset-2 col-md-2">Имя:</p>

		<p class="col-md-2"><?= $user['first_name'] ?></p>        <br>

		<p class="col-md-offset-2 col-md-2">Фамилия:</p>

		<p class="col-md-2"><?= $user['last_name'] ?></p>        <br>

		<p class="col-md-offset-2 col-md-2">E-mail:</p>

		<p class="col-md-2"><?= $user['email'] ?></p>        <br>
	</div>
	<h2 class="text-center">Подключенные соцсети</h2>

	<?=
	Form::button('Подключить новую соцсеть', array(
		'class' => 'btn btn-primary',
	));?>
</div>
