<script src="/packages/fintech-fab/qiwi-shop/js/ActionCreateOrder.js"></script>
<div class="text-center inner">
	<h3>Новый заказ</h3>

	<?=
	Form::open(array(
		'class'  => 'form-horizontal',
		'role'   => 'form',
		'method' => 'post',

	)); ?>

	<div class="form-group row">
		<?= Form::label('inputSum', 'Наименование товара:', array('class' => 'col-sm-4 control-label')) ?>

		<div class="col-sm-4">
			<?=
			Form::input('text', 'item', '', array(
				'placeholder' => 'Наименование',
				'class'       => 'form-control',
				'id'          => 'inputItem',
				'required'    => 'required',
			));
			?>
		</div>
		<div id="errorItem" class="error col-sm-4 text-left"></div>
	</div>
	<div class="form-group row">
		<?= Form::label('inputSum', 'Сумма(руб):', array('class' => 'col-sm-4 control-label')) ?>

		<div class="col-sm-4">
			<?=
			Form::input('text', 'sum', '', array(
				'placeholder' => 'Сумма заказа',
				'class'       => 'form-control',
				'id'          => 'inputSum',
				'required'    => 'required',
			));
			?>
		</div>
		<div id="errorSum" class="error col-sm-4 text-left"></div>
	</div>
	<div class="form-group row">
		<?= Form::label('inputTel', 'Телефон:', array('class' => 'col-sm-4 control-label')) ?>
		<div class="col-sm-4">
			<?=
			Form::input('tel', 'tel', '', array(
				'placeholder' => '+12345678900',
				'class'       => 'form-control',
				'id'          => 'inputTel',
				'required'    => 'required',
			));
			?>
		</div>
		<div id="errorTel" class="error col-sm-4 text-left"></div>

	</div>
	<div class="form-group row">
		<?= Form::label('inputComment', 'Комментарий:', array('class' => 'col-sm-4 control-label')) ?>
		<div class="col-sm-4">
			<?=
			Form::textarea('comment', '', array(
				'placeholder' => 'Комментарий к заказу (если нужен)',
				'class'       => 'form-control',
				'id'          => 'inputComment',
				'rows'        => '5',
			));
			?>
		</div>
		<div id="errorComment" class="error col-sm-4 text-left"></div>
	</div>
	<div id="errorCommon" class="error text-center"></div>
	<br>
	<?=
	Form::button('Оформить заказ', array(
		'id'    => 'createOrder',
		'class' => 'btn btn-success',
	));
	?>

	<?= Form::close(); ?>
</div>
<div id="message" class="row"></div>