<?php

use FintechFab\QiwiGate\Models\Merchant;

/**
 * @var Merchant $merchant
 */

?>
<div class="modal fade" id="changeDataModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Введите новые данные</h4>
			</div>
			<div class="modal-body">
				<?=
				Form::open(array(
					'class'  => 'form-horizontal',
					'role'   => 'form',
					'method' => 'post',

				)); ?>
				<div class="form-group row">
					<?= Form::label('inputCallback', 'Callback', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'callback', $merchant->callback_url, array(
							'placeholder' => 'Callback адрес',
							'class'       => 'form-control',
							'id'          => 'inputCallback',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorCallback" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputUsername', 'Имя', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'username', $merchant->username, array(
							'placeholder' => 'Имя пользователя',
							'class'       => 'form-control',
							'id'          => 'inputUsername',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorUsername" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputPassword', 'Новый пароль', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('password', 'password', '', array(
							'placeholder' => 'Введите новый пароль',
							'class'       => 'form-control',
							'id'          => 'inputPassword',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorPassword" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputConfirmPassword', 'Новый пароль', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('password', 'confirmPassword', '', array(
							'placeholder' => 'Повторите новый пароль',
							'class'       => 'form-control',
							'id'          => 'inputConfirmPassword',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorConfirmPassword" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputOldPassword', 'Текущий пароль', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('password', 'oldPassword', '', array(
							'placeholder' => 'Введите текущий пароль',
							'class'       => 'form-control',
							'id'          => 'inputOldPassword',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorOldPassword" class="text-danger text-center"></div>
				</div>

				<?= Form::close(); ?>
			</div>
			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'changeAccountData',
					'class' => 'btn btn-success payReturnModal',
				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

