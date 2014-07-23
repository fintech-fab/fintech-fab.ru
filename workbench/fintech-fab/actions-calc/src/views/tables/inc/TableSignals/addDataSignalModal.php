<?php

use FintechFab\ActionsCalc\Models\Signal;

/**
 * @var Signal $signal
 */

?>
<div class="modal fade" id="addDataSignalModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Введите новые данные</h4>
			</div>

			<div class="modal-body">

				<div class="form-group row">
					<?= Form::label('inputNameAdd', 'Имя', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'name', '', array(
							'placeholder' => 'Название',
							'class'       => 'form-control',
							'id'          => 'inputNameAdd',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorNameAdd" class="text-danger text-center"></div>
				</div>

				<div class="form-group row">
					<?= Form::label('inputSidAdd', 'Sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'sid', '', array(
							'placeholder' => 'Sid',
							'class'       => 'form-control',
							'id'          => 'inputSidAdd',
						));
						?>
					</div>
					<div id="errorSidAdd" class="text-danger text-center"></div>
				</div>

			</div>

			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'AddSignalTable',
					'class' => 'btn btn-success',
				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

