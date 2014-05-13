<?php
/**
 * var int $order_id
 */
?>
<div class="modal fade" id="payReturn" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Возврат оплаты</h4>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<?= Form::label('inputSum', 'Сумма', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::input('text', 'sum', '', array(
							'placeholder' => 'Сумма возврата',
							'class'       => 'form-control',
							'id'          => 'inputSum',
							'required'    => 'required',
						));
						?>
					</div>
					<div id="errorSum" class="error text-center"></div>
				</div>
				<div class="form-group row">
					<?= Form::label('inputComment', 'Коммент:', array('class' => 'col-sm-3 control-label')) ?>
					<div class="col-sm-9">
						<?=
						Form::textarea('comment', '', array(
							'placeholder' => 'Комментарий к возврату (если нужен)',
							'class'       => 'form-control',
							'id'          => 'inputComment',
							'rows'        => '5',
						));
						?>
					</div>
					<div id="errorComment" class="error text-center"></div>
				</div>
				<div class="text-center">
					<?=
					Form::button('Возврат отплаты', array(
						'class' => 'btn btn-danger payReturnModal',
					)); ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

