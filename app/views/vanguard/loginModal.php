<?php

?>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Вход на сайт</h4>
			</div>
			<div class="modal-body">
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
				<?= Form::close(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Login</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>
		</div>
	</div>
</div>

