<?php

use FintechFab\ActionsCalc\Models\Rule;
use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Signal;


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
					<?= Form::label('inputEventSid', 'event_sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">

						<div class="demo">
							<div class="ui-widget">
								<?=
								Form::select('EventSid', Event::getEventSid(), '', array(
									'class'    => 'form-control',
									'id'       => 'inputEventSid',
									'required' => 'required',
								));
								?>
							</div>
						</div>
					</div>
					<div id="errorEventSid" class="text-danger text-center"></div>
				</div>

				<div class="form-group row">
					<?= Form::label('inputRule', 'Условие', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'rule', '', array(
							'placeholder' => 'rule',
							'class'       => 'form-control',
							'id'          => 'inputRule',
						));
						?>
					</div>
					<div id="errorRule" class="text-danger text-center"></div>
				</div>


				<div class="form-group row">

					<?= Form::label('inputSignalSid', 'signal_sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<div class="demo">
							<div class="ui-widget">
							<?=
						Form::select('SignalSid', Signal::getSignalSid(), '', array(
							'class'    => 'form-control',
							'id'       => 'inputSignalSid',
							'required' => 'required',
						));
						?>
							</div>
						</div>
					</div>
					<div id="errorSignalSid" class="text-danger text-center"></div>
				</div>


			</div>

			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'actionBtn',
					'class' => 'btn btn-success changeDataModal',
				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

