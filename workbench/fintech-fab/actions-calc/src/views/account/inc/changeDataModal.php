<?php

use FintechFab\ActionsCalc\Models\Terminal;

/**
 * @var Terminal $terminal
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
				<div class="form-group row">
					<?= Form::label('inputUsername', 'Имя', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'username', $terminal->name, array(
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
					<?= Form::label('inputUrl', 'Url', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'url', $terminal->url, array(
							'placeholder' => 'http://',
							'class'       => 'form-control',
							'id'          => 'inputUrl',
						));
						?>
					</div>
					<div id="errorUrl" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputQueue', 'Queue', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'queue', $terminal->queue, array(
							'placeholder' => 'Queue',
							'class'       => 'form-control',
							'id'          => 'inputQueue',
						));
						?>
					</div>
					<div id="errorQueue" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputKey', 'Key', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'key', $terminal->key, array(
							'placeholder' => 'Key',
							'class'       => 'form-control',
							'id'          => 'inputKey',
						));
						?>
					</div>
					<div id="errorKey" class="text-danger text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputPassword', 'Новый пароль', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('password', 'password', '', array(
							'placeholder' => 'Введите новый пароль',
							'class'       => 'form-control',
							'id'          => 'inputPassword',
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

			</div>
			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'changeAccountData',
					'class' => 'btn btn-success changeDataModal',
				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

