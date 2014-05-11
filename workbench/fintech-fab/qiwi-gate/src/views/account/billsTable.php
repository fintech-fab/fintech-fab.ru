<?php
/**
 * @var Bill $bills
 */
use FintechFab\QiwiGate\Models\Bill;

?>
<?= View::make('ff-qiwi-shop::qiwiShop.inc.payReturnModal') ?>
<script src="/packages/fintech-fab/qiwi-gate/js/ActionAccountBillsTable.js"></script>

<table class="table table-striped table-hover" id="billsTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Пользователь</b></td>
		<td><b>Сумма</b></td>
		<td><b>Валюта</b></td>
		<td><b>Комментарий</b></td>
		<td><b>Срок действия</b></td>
		<td><b>Провайдер</b></td>
		<td><b>Статус</b></td>
		<td><b>Возврат</b></td>
	</tr>
	<?php foreach ($bills as $bill): ?>
		<tr>
			<td><?= $bill->id ?></td>
			<td><?= $bill->user ?></td>
			<td><?= $bill->amount ?></td>
			<td><?= $bill->ccy ?></td>
			<td><?= $bill->comment ?></td>
			<td><?= $bill->lifetime ?></td>
			<td><?= $bill->prv_name ?></td>
			<td><?= $bill->status ?></td>
			<td>
				<?php if ($bill->status == 'paid') {
					echo Form::button('Возврат', array(
						'type'  => 'button',
						'class' => 'btn btn-primary btn-sm refund-button',
						'id'    => 'bill-refunds_' . $bill->id,
					));
				} ?>
			</td>
		</tr>
		<tr id="table-refund-<?= $bill->id ?>" style="display: none;">
			<td colspan="9" class="container text-right"></td>
		</tr>

	<?php endforeach ?>
</table>
<?= $bills->links() ?>
