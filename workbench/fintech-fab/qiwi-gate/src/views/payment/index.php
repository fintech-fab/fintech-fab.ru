<?php
/**
 * @var Bill $bill
 */
use FintechFab\QiwiGate\Models\Bill;

?>

<div class="row container">

	<h2>Оплата счёта</h2><br>

	<p>Вы хотите оплатить счёт № <?= $bill->id ?> на сумму <?= $bill->amount ?> <?= $bill->ccy ?>.</p>

	<p>Счёт выставлен на телефон  <?= $bill->user ?></p>


	<?php

	echo Form::button('Оплатить', array(
		'id'    => 'payBill',
		'class' => 'btn btn-success',
	));

	?>

	<div id="message"></div>
	<div id="error"></div>

</div>