<?php
/**
 * @var Order $orders
 */
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Widgets\MakeButton;

?>
<?= View::make('ff-qiwi-shop::qiwiShop.inc.payReturnModal') ?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/ActionTableButtons.js') ?>
</script>

<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Название</b></td>
		<td><b>Сумма</b></td>
		<td><b>Комментарий</b></td>
		<td><b>Статус</b></td>
		<td><b>Сумма возвратов</b></td>
		<td><b>Действия с заказом</b></td>
	</tr>
	<?php foreach ($orders as $order): ?>
		<?php $arr = MakeButton::displayTable($order) ?>
		<tr>
			<td><?= $order->id ?></td>
			<td><?= $order->item ?></td>
			<td><?= $order->sum ?></td>
			<td><?= $order->comment ?></td>
			<td><?= $arr['status'] ?></td>
			<td><?= $arr['sumReturn'] ?></td>
			<td><?= $arr['activity'] ?></td>
		</tr>

	<?php endforeach ?>
</table>
<?= $orders->links() ?>
<div id="message" class="text-center"></div>
