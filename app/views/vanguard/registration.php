<?php
/**
 * @var string $userMessage
 */
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
			<label for="inputFirstName" class="col-sm-2 control-label">Имя</label>

			<div class="col-sm-8">
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
			<label for="inputLastName" class="col-sm-2 control-label">Фамилия</label>

			<div class="col-sm-8">
				<?=
				Form::input('text', 'last_name', '', array(
					'placeholder' => 'Фамилия',
					'class'       => 'form-control',
					'id'          => 'inputLastName',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
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
			<div class="col-sm-offset-7 col-sm-2">
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
			<a href=""><img src="/assets/ico/vk48.png" alt="" /></a>
			<a href=""><img src="/assets/ico/fb48.png" alt="" /></a>
			<a href=""><img src="/assets/ico/gp48.png" alt="" /></a>
		</div>
	</div>
</div>