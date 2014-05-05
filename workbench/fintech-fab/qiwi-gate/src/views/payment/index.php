<?php
/**
 * @var Bill $bill
 */
use FintechFab\QiwiGate\Models\Bill;

?>

<h2 class="text-center">Оплата счёта</h2><br>

<p class="text-center">Вы хотите оплатить счёт № <?= $bill->id ?> на сумму <?= $bill->amount ?> <?= $bill->ccy ?>.</p>

<p class="text-center">Счёт выставлен на телефон  <?= $bill->user ?></p>
<?=
Form::open(array(
	'class' => 'text-center',
))?>
<div class="form-group row">
	<?= Form::label('inputTel', 'Введите телефон:', array('class' => 'col-sm-4 control-label text-right')) ?>

	<div class="col-sm-4">
		<?=
		Form::input('text', 'user', '', array(
			'placeholder' => '+79001234567',
			'class'       => 'form-control',
			'id'          => 'inputTel',
			'required'    => 'required',
		));
		?>
	</div>
	<div id="error" class="error col-sm-4 text-left text-danger"></div>
</div>
<?=
Form::button('Оплатить', array(
	'id'    => 'payBill',
	'class' => 'btn btn-success',
));?>
<?= Form::close() ?>
<div id="message"></div>

