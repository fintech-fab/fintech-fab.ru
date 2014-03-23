<?php
/**@var array $user * */
use FintechFab\Components\Social;
use FintechFab\Models\User;
use FintechFab\Widgets\LinksInMenu;

$user = User::find(Auth::user()->id)->toArray();
$userSocial = User::find($user['id'])->SocialNetworks()->get()->toArray();

/*echo count($userSocial) ;
echo'<pre>';
print_r($userSocial);
print_r($user);
echo'</pre>';
die();*/

?>
<div class="jumbotron ">
	<?= LinksInMenu::linkForAdmin() ?>
	<h2 class="text-center">Профиль</h2><br>

	<div class="row">

		<div class="col-md-offset-1 col-md-3">
			<img src="<?= $user['photo'] ?>" class="img-rounded">
		</div>
		<div class="col-md-offset-1 col-md-4">
			<p class="col-md-6">Имя:</p>

			<p class="col-md-6"><?= $user['first_name'] ?></p><br>

			<p class="col-md-6">Фамилия:</p>

			<p class="col-md-6"><?= $user['last_name'] ?></p><br>

			<p class="col-md-6">E-mail:</p>

			<p class="col-md-6"><?= $user['email'] ?></p><br>
		</div>
	</div>
	<div class="row">
		<h2 class="text-center">Подключенные соцсети</h2><br>
		<?php for ($i = 0;
		$i < count($userSocial);
		$i++): ?>
		<div class="col-md-offset-2 col-md-2">
			<img src="<?= $userSocial[$i]['photo'] ?>" class="img-thumbnail" width="100">
		</div>
		<div class="col-md-offset-1 col-md-4">
			<p class="col-md-6">Имя:</p>

			<p class="col-md-6"><?= $userSocial[$i]['first_name'] ?></p><br>

			<p class="col-md-6">Фамилия:</p>

			<p class="col-md-6"><?= $userSocial[$i]['last_name'] ?></p><br><br>
		</div>
	</div>
	<br>
	<?php endfor ?>
	<p>Подключить социальную сеть:
		<a href="<?= Social::linkForSocNet('vk') ?>"><img src="/assets/ico/vk32.png" alt="" /></a>
		<a href="<?= Social::linkForSocNet('fb') ?>"><img src="/assets/ico/fb32.png" alt="" /></a> <a href="<?= Social::linkForSocNet('gp') ?>"><img src="/assets/ico/gp32.png" alt="" /></a>

	</p>
</div>
