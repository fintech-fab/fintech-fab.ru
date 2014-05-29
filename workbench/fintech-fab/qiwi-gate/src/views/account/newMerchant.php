<?php
/**
 * @var int $user_id
 */
?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/ActionAccountReg.js') ?>
</script>
<div id="message"></div>
<div class="col-sm-offset-3 col-md-6 inner">
	<div class="content">
		<h2 class="text-center">Регистрация в системе QIWI</h2>
		<?=
		Form::open(array(
			'route'  => 'postAccountReg',
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',
		)); ?>
		<div class="form-group row">
			<label for="inputId" class="col-sm-3 control-label">Ваш ID (логин)</label>

			<div class="col-sm-7">
				<?=
				Form::input('text', 'userId', $user_id, array(
					'class'    => 'form-control',
					'id'       => 'inputId',
					'required' => '',
					'disabled' => '',
				));
				?>
			</div>
			<div class="text-danger" id="errorId"></div>
		</div>
		<div class="form-group row">
			<label for="inputUsername" class="col-sm-3 control-label">Имя</label>

			<div class="col-sm-7">
				<?=
				Form::input('text', 'username', '', array(
					'placeholder' => 'Имя пользователя',
					'class'       => 'form-control',
					'id'          => 'inputUsername',
					'required'    => '',
				));
				?>
				<div class="text-danger text-center" id="errorUsername"></div>
			</div>
		</div>

		<div class="form-group row">
			<label for="inputCallback" class="col-sm-3 control-label">Адрес для callback</label>

			<div class="col-sm-7">

				<?=
				Form::input('url', 'callback', '', array(
					'placeholder' => 'Callback',
					'class'       => 'form-control',
					'id'          => 'inputCallback',
					'required'    => '',
				));
				?>
				<div class="text-danger text-center" id="errorCallback"></div>
			</div>
		</div>
		<div class="form-group row">
			<label for="inputPassword" class="col-sm-3 control-label">Пароль</label>

			<div class="col-sm-7">
				<?=
				Form::input('password', 'password', '', array(
					'placeholder' => 'Пароль',
					'class'       => 'form-control',
					'id'          => 'inputPassword',
					'required'    => '',
				));
				?>
				<div class="text-danger text-center" id="errorPassword"></div>
			</div>
		</div>
		<div class="form-group row">
			<label for="inputConfirmPassword" class="col-sm-3 control-label">Повтор пароля</label>

			<div class="col-sm-7">
				<?=
				Form::input('password', 'confirmPassword', '', array(
					'placeholder' => 'Пароль',
					'class'       => 'form-control',
					'id'          => 'inputConfirmPassword',
					'required'    => '',
				));
				?>
				<div class="text-danger text-center" id="errorConfirmPassword"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-7 col-sm-3">
				<?=
				Form::button('Регистрация', array(
					'id'    => 'accountRegSubmit',
					'type'  => 'button',
					'class' => 'btn btn-default',
				));
				?>
			</div>
		</div>
		<?= Form::close(); ?>
	</div>
</div>