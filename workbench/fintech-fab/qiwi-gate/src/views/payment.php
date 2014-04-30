<?php
/**
 * @var Bill $bill
 */
use FintechFab\QiwiGate\Models\Bill;

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>FINTECH_FAB::QiwiShop</title>

	<link type="text/css" href="//code.jquery.com/ui/1.10.4/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.0.min.js "></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.min.js "></script>

	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js "></script>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
</head>
<body>
<h2 class="text-center">Оплата счёта</h2><br>

<p class="text-center">Вы хотите оплатить счёт № <?= $bill->id ?> на сумму <?= $bill->amount ?> <?= $bill->ccy ?>.</p>

<p class="text-center">Счёт выставлен на телефон  <?= $bill->user ?> .</p>
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
	<div id="errorItem" class="error col-sm-4 text-left"></div>
</div>
<?= Form::hidden('bill_id', $bill->bill_id) ?>
<?=
Form::submit('Оплатить', array(
	'id'    => 'payBill',
	'class' => 'btn btn-success',
));?>
<?= Form::close() ?>
</body>
</html>
