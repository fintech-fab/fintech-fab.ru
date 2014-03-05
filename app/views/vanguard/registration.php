<?php
/**
 * @var string $userMessage
 */
?>
<div class="col-md-offset-1 col-md-10 inner">
	<div class="content">
		<?=
		Form::open(array(
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',
		)); ?>
		<div class="form-group">
			<label for="inputEmail" class="col-sm-2 control-label">Email</label>

			<div class="col-sm-8">

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
			<label for="inputPassword" class="col-sm-2 control-label">Пароль</label>

			<div class="col-sm-8">
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
			<div class="col-sm-offset-2 col-sm-10">
				<?=
				Form::button('Sign in', array(
					'type'  => 'submit',
					'class' => 'btn btn-default',
				));
				?>
			</div>
		</div>
		<?= Form::close(); ?>
	</div>
</div>