<?php

use FintechFab\ActionsCalc\Models\Event;
use FintechFab\ActionsCalc\Models\Signal;


?>
<div class="modal fade" id="addDataRuleModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
							'id'          => 'inputNameAddRule',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorNameAddRule" class="text-danger text-center"></div>
				</div>

				<div class="form-group row">
					<?= Form::label('inputEventSidAdd', 'event_sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<div class="EventSid">
							<?=
							Form::select('EventSid', Event::getEventSid(), '', array(
								'class'    => 'form-control',
								'id'       => 'inputEventSidAdd',
								'required' => 'required',
							));
							?>
						</div>
					</div>
					<div id="errorEventSidAdd" class="text-danger text-center"></div>
				</div>

				<div class="form-group row">
					<?= Form::label('inputRuleAdd', 'Условие', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'rule', '', array(
							'placeholder' => 'rule',
							'class'       => 'form-control',
							'id'          => 'inputRuleAdd',
						));
						?>
					</div>
					<div id="errorRuleAdd" class="text-danger text-center"></div>
				</div>


				<div class="form-group row">
					<?= Form::label('inputSignalSidAdd', 'signal_sid', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<div class="SignalSid">
							<?=
							Form::select('SignalSid', Signal::getSignalSid(), '', array(
								'class'    => 'form-control inputSignalSidAdd',
								'id'       => 'inputSignalSidAdd',
								'required' => 'required',
							));
							?>
						</div>
					</div>
					<div id="errorSignalSidAdd" class="text-danger text-center"></div>
				</div>
				<div class="addSignal">
					<?=
					Form::button('Добавить сигнал', array(
						'id'    => 'addSignalInput',
						'class' => 'btn btn-success',
					)); ?>

					<button id="removeLastSignal" type="button" class="btn btn-danger">Удалить сигнал</button>

				</div>

			</div>

			<div class="modal-footer">
				<?=
				Form::button('Сохранить', array(
					'id'    => 'actionBtn',
					'class' => 'btn btn-success changeDataModal addDataRuleTable',

				)); ?>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

