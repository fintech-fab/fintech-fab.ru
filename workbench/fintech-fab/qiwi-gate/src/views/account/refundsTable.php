<?php
/**
 * @var Refund $refunds
 */
use FintechFab\QiwiGate\Models\Refund;

?>
<div class="row">
	<div class="col-md-offset-8 col-md-4">
		<div class="col-md-3"><b>ID</b></div>
		<div class="col-md-4"><b>Сумма</b></div>
		<div class="col-md-4"><b>Статус</b></div>
	</div>
</div>
<?php foreach ($refunds as $refund): ?>
	<div class="row">
		<div class="col-md-offset-8 col-md-4">
			<div class="col-md-3"><?= $refund->refund_id ?></div>
			<div class="col-md-4"><?= $refund->amount ?></div>
			<div class="col-md-4"><?= $refund->status ?></div>
		</div>
	</div>

<?php endforeach ?>


