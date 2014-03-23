<?php
/**
 * @var string $userMessage
 */
use FintechFab\Components\Social;

?>
<div class="col-md-offset-3 col-md-6 inner">
	<div class="content">
		<h2 class="text-center">Регистрация на нашем сайте</h2>
		<?=
		Form::open(array(
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',
		)); ?>
		<div class="form-group">
			<label for="inputFirstName" class="col-sm-3 control-label">Имя</label>

			<div class="col-sm-7">
				<?=
				Form::input('text', 'first_name', '', array(
					'placeholder' => 'Имя',
					'class'       => 'form-control',
					'id'          => 'inputFirstName',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputLastName" class="col-sm-3 control-label">Фамилия</label>

			<div class="col-sm-7">
				<?=
				Form::input('text', 'last_name', '', array(
					'placeholder' => 'Фамилия',
					'class'       => 'form-control',
					'id'          => 'inputLastName',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail" class="col-sm-3 control-label">Email</label>

			<div class="col-sm-7">

				<?=
				Form::input('email', 'email', '', array(
					'placeholder' => 'Email',
					'class'       => 'form-control',
					'id'          => 'inputEmail',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword" class="col-sm-3 control-label">Пароль</label>

			<div class="col-sm-7">
				<?=
				Form::input('password', 'password', '', array(
					'placeholder' => 'Пароль',
					'class'       => 'form-control',
					'id'          => 'inputPassword',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputPassword" class="col-sm-3 control-label">Повтор пароля</label>

			<div class="col-sm-7">
				<?=
				Form::input('password', 'passwordRepeat', '', array(
					'placeholder' => 'Пароль',
					'class'       => 'form-control',
					'id'          => 'inputPassword',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-7 col-sm-3">
				<?=
				Form::button('Регистрация', array(
					'type'  => 'submit',
					'class' => 'btn btn-default',
				));
				?>
			</div>
		</div>
		<?= Form::close(); ?>
		<hr>
		<div class="text-center">
			<h3>Регистрация через социалные сети</h3>
			<a href="<?= Social::linkForSocNet('vk') ?>"><img src="/assets/ico/vk48.png" alt="" /></a>
			<a href="<?= Social::linkForSocNet('fb') ?>"><img src="/assets/ico/fb48.png" alt="" /></a>
			<a href="<?= Social::linkForSocNet('gp') ?>"><img src="/assets/ico/gp48.png" alt="" /></a>
		</div>
	</div>
</div>