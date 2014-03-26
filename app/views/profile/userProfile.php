<?php
/**@var array $user * */
use FintechFab\Components\Social;
use FintechFab\Models\User;
use FintechFab\Widgets\UsersPhoto;

$user = User::find(Auth::user()->id)->toArray();
$userSocial = User::find($user['id'])->SocialNetworks()->get()->toArray();
$socialNets = array();

?>
<script src="/js/imageuploader.js" type="text/javascript"></script>
<div id="profile">
	<h2 class="text-center">Профиль</h2><br>

	<div class="row">
		<div id="photo" class="col-md-offset-1 col-md-3">
			<?= UsersPhoto::getPhoto() ?>
		</div>
		<div id="drop-files" class="col-md-4">
			<?=
			Form::open(array(
				'id'     => 'formForFile',
				'class'  => 'form-horizontal',
				'role'   => 'form',
				'method' => 'post',
				'action' => 'upload/image',
				'target' => 'iframe-name',
			)); ?>
			<div id="dropZone" class="text-center">
					<span class="text">
						Для загрузки, перетащите файл сюда, или
					</span><br>
				<?=
				Form::file('image', array(
					'id'    => 'upload_btn',
					'class' => 'center-block',
				))?>
			</div>
			<?= Form::close(); ?>
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
	<h2 class="text-center">Подключенные соцсети</h2><br>
	<?php for ($i = 0; $i < count($userSocial); $i++):
		$socialNets[$userSocial[$i]['social_net_name']] = true;?>
		<div class="row">
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
	<p class="text-center">Подключить социальную сеть:
		<?php if (!isset($socialNets['vk'])): ?>
			<a href="<?= Social::linkForSocNet('vk') ?>"><img src="assets/ico/fb32.png" alt="" /></a>
		<?php endif ?>
		<?php if (!isset($socialNets['fb'])): ?>
			<a href="<?= Social::linkForSocNet('fb') ?>"><img src="assets/ico/fb32.png" alt="" /></a>
		<?php endif ?>
		<?php if (!isset($socialNets['gp'])): ?>
			<a href="<?= Social::linkForSocNet('gp') ?>"><img src="assets/ico/gp32.png" alt="" /></a>
		<?php endif ?>
	</p>
</div>

