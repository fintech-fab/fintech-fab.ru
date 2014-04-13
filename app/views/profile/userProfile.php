<?php
/**@var array $user * */
use FintechFab\Components\Social;
use FintechFab\Models\User;
use FintechFab\Widgets\UsersPhoto;

$user = User::find(Auth::user()->id)->toArray();
$userSocial = User::find($user['id'])->SocialNetworks()->get()->toArray();
$socialNets = array();

?>
<script src="/js/ActionForUser.js"></script>
<script type="text/javascript" src="/js/jquery.damnUploader.min.js"></script>
<script src="/js/interafce.js"></script>
<script src="/js/uploader-setup.js"></script>
<div id="profile">
	<h2 class="text-center">Профиль</h2><br>

	<div class="row">
		<div id="photo" class="col-md-offset-1 col-md-4">
			<?= UsersPhoto::getPhoto() ?>
		</div>
		<div class="col-md-5 photo_upload">
			<div class="well well-lg auto-tip" id="drop-box">
				<p>Перетащите файл в эту область, или</p>
				<?=
				Form::open(array(
					'id'      => 'upload-form',
					'class'   => 'form-inline',
					'role'    => 'form',
					'method'  => 'post',
					'action'  => 'upload/image',
					'enctype' => 'multipart/form-data',
				)); ?>
				<div class="form-group for_input_file">
					<?=
					Form::file('image', array(
						'id'    => 'file-input',
						'class' => 'form-control auto-tip',
					))?>
				</div>
				<div class="row">
					<div class="checkbox col-md-offset-1 col-md-5">
						<label> <input type="checkbox" id="previews-checker" checked="checked"> Предпросмотр фото
						</label> <br> <label> <input type="checkbox" id="autostart-checker"> Автостарт загрузки </label>
					</div>
					<button id="send-btn" type="submit" class="btn btn-primary btn-std col-md-2">Готово</button>
					<button id="clear-btn" class="btn btn-danger btn-std col-md-2">Отмена</button>
				</div>
				<?= Form::close(); ?>
			</div>
			<div class="">
				<h3>Upload queue</h3>
				<table class="table">
					<thead>
					<tr>
						<th>Preview</th>
						<th>Original filename</th>
						<th>Size</th>
						<th>Status</th>
						<th></th>
					</tr>
					</thead>
					<tbody id="upload-rows"></tbody>
				</table>
			</div>
		</div>
		<div class="col-md-4">
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
			<a href="<?= Social::linkForSocNet('vk') ?>"><img src="assets/ico/vk32.png" alt="" /></a>
		<?php endif ?>
		<?php if (!isset($socialNets['fb'])): ?>
			<a href="<?= Social::linkForSocNet('fb') ?>"><img src="assets/ico/fb32.png" alt="" /></a>
		<?php endif ?>
		<?php if (!isset($socialNets['gp'])): ?>
			<a href="<?= Social::linkForSocNet('gp') ?>"><img src="assets/ico/gp32.png" alt="" /></a>
		<?php endif ?>
	</p>
</div>

