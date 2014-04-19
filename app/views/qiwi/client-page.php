<?php
?>
<script src="/js/ActionForClient.js"></script>
<div class="text-center">

	<button type="button" class="btn btn-primary buttonWithMargin" id="buy">Купить в магазине</button>
	<br>

	<div id="buy_window" class="">
		<h3>Сумма заказа</h3>

		<?=
		Form::open(array(
			'action' => 'client-page',
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',

		)); ?>
		<p>Укажите, на какую сумму вы хотите сделать покупку в магазине.</p>

		<div class="form-group row">
			<?= Form::label('inputSum', 'Сумма(руб):', array('class' => 'col-sm-5 control-label')) ?>

			<div class="col-sm-2">
				<?=
				Form::input('text', 'sum', '', array(
					'placeholder' => 'Сумма',
					'class'       => 'form-control',
					'id'          => 'inputSum',
					'required'    => 'required',
				));
				?>
			</div>
			<div id="errorSum" class="error pull-left"></div>
		</div>
		<div class="form-group row">
			<?= Form::label('inputTel', 'Телефон:', array('class' => 'col-sm-5 control-label')) ?>
			<div class="col-sm-2">
				<?=
				Form::input('tel', 'tel', '', array(
					'placeholder' => '+12345678900',
					'class'       => 'form-control',
					'id'          => 'inputTel',
					'required'    => 'required',
				));
				?>
			</div>
			<div id="errorTel" class="error pull-left"></div>

		</div>
		<div class="form-group row">
			<?= Form::label('inputComment', 'Комментарий:', array('class' => 'col-sm-5 control-label')) ?>
			<div class="col-sm-2">
				<?=
				Form::textarea('comment', '', array(
					'placeholder' => 'Комментарий к счёту (если нужен)',
					'class'       => 'form-control',
					'id'          => 'inputComment',
					'rows'        => '5',
				));
				?>
			</div>
		</div>
		<input id="user_id" name="userID" type="hidden" value="1">
		<button type="button" class="btn btn-success" id="buy-operation">Купить</button>
		<?= Form::close(); ?>
	</div>
	<button type="button" class="btn btn-primary buttonWithMargin" id="check-bill">Счета для оплаты</button>
	<br>
	<button type="button" class="btn btn-primary buttonWithMargin" id="pay-bill">Оплатить</button>
	<br>
</div>
