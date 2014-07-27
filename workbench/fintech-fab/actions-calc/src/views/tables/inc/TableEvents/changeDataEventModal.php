<?php

use FintechFab\ActionsCalc\Models\Event;

/**
 * @var Event $event
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
					<?= Form::label('inputName', 'Имя', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'name', '', array(
							'placeholder' => 'Название',
							'class'       => 'form-control',
							'id'          => 'inputName',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorName" class="text-danger text-center"></div>
				</div>

				<div class="form-group row">
					<?= Form::label('inputSid', 'Sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'sid', '', array(
							'placeholder' => 'Sid',
							'class'       => 'form-control',
							'id'          => 'inputSid',
						));
						?>
					</div>
					<div id="errorSid" class="text-danger text-center"></div>
				</div>

			</div>

			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'saveEditTableEvent',
					'class' => 'btn btn-success changeDataModal',
				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

