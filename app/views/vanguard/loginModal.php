<?php
use FintechFab\Components\Social;

?>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Вход на сайт</h4>
			</div>
			<div class="modal-body">
				<?=
				Form::open(array(
					'action' => 'auth',
					'class'  => 'form-horizontal',
					'role'   => 'form',
					'method' => 'post',

				)); ?>
				<div class="form-group">
					<?= Form::label('inputEmail', 'Email', array('class' => 'col-sm-3 control-label')) ?>

					<div class="col-sm-9">

						<?=
						Form::input('email', 'email', '', array(
							'placeholder' => 'Email',
							'class'       => 'form-control',
							'id'          => 'inputEmail',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorEmail" class="errorModal text-center"></div>
				</div>
				<div class="form-group">
					<?= Form::label('inputPassword', 'Пароль', array('class' => 'col-sm-3 control-label')) ?>

					<div class="col-sm-9">
						<?=
						Form::input('password', 'password', '', array(
							'placeholder' => 'Пароль',
							'class'       => 'form-control',
							'id'          => 'inputPassword',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorPassword" class="errorModal text-center"></div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9 checkbox">
						<label>
							<?= Form::checkbox('remember', 'true'); ?>Запомнить меня </label>
					</div>
					<div id="errorPassword" class="errorModal text-center"></div>
				</div>
				<div class="form-group">
					<p class="col-sm-8">Не зарегистрирован?<br><a href="/registration">Регистрация</a></p>

					<div class=" col-sm-4">
						<?=
						Form::button('Login', array(
							'type'  => 'button',
							'class' => 'btn btn-primary',
							'id'    => 'btn-login',
						));
						?>
					</div>
				</div>
				<?= Form::close(); ?>
				<hr>

				<div class="text-center">
					<h4 class="text-center">Вход через социальные сети</h4>

					<a href="<?= Social::linkForSocNet('vk') ?>"><img src="/assets/ico/vk32.png" alt="" /></a>
					<a href="<?= Social::linkForSocNet('fb') ?>"><img src="/assets/ico/fb32.png" alt="" /></a>
					<a href="<?= Social::linkForSocNet('gp') ?>"><img src="/assets/ico/gp32.png" alt="" /></a>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

