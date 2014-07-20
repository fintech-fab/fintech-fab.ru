<?php
/**
 * @var integer $termId
 */
?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/ActionAccountReg.js') ?>
</script>
<div id="message"></div>
<div class="col-sm-offset-3 col-md-6 inner">
	<div class="content">
		<h2 class="text-center">Регистрация в калькуляторе поведения</h2>

		<div class="form-group row">
			<label for="inputId" class="col-sm-3 control-label">Ваш ID (логин)</label>

			<div class="col-sm-7">
				<?=
				Form::input('text', 'termId', $termId, array(
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
			<label for="inputCallback" class="col-sm-3 control-label">URL</label>

			<div class="col-sm-7">

				<?=
				Form::input('url', 'url', 'http://', array(
					'placeholder' => 'http://',
					'class'       => 'form-control',
					'id'          => 'inputUrl',
				));
				?>
				<div class="text-danger text-center" id="errorUrl"></div>
			</div>
		</div>

		<div class="form-group row">
			<label for="inputQueue" class="col-sm-3 control-label">Queue</label>

			<div class="col-sm-7">

				<?=
				Form::input('text', 'queue', '', array(
					'placeholder' => 'Класс для выполнения очереди',
					'class'       => 'form-control',
					'id'          => 'inputQueue',
				));
				?>
				<div class="text-danger text-center" id="errorQueue"></div>
			</div>
		</div>

		<div class="form-group row">
			<label for="inputKey" class="col-sm-3 control-label">Key</label>

			<div class="col-sm-7">

				<?=
				Form::input('text', 'key', '', array(
					'placeholder' => 'Если не указать, будет выдан случайный',
					'class'       => 'form-control',
					'id'          => 'inputKey',
				));
				?>
				<div class="text-danger text-center" id="errorKey"></div>
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
	</div>
</div>