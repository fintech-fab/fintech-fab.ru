<?php
/**
 * @var Order $orders
 */
use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Widgets\MakeButton;

$i = 1;
?>
<script src="/packages/fintech-fab/qiwi-shop/js/ActionTableButtons.js"></script>

<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>№</b></td>
		<td><b>ID</b></td>
		<td><b>Название</b></td>
		<td><b>Сумма</b></td>
		<td><b>Комментарий</b></td>
		<td><b>Статус</b></td>
		<td><b>Телефон</b></td>
		<td><b>Действия с заказом</b></td>
	</tr>
	<?php foreach ($orders as $order): ?>
		<?php $arr = MakeButton::displayTable($order) ?>
		<tr>
			<td><?= $i ?></td>
			<td><?= $order->id ?></td>
			<td><?= $order->item ?></td>
			<td><?= $order->sum ?></td>
			<td><?= $order->comment ?></td>
			<td><?= $arr['status'] ?></td>
			<td><?= $order->tel ?></td>
			<td><?= $arr['activity'] ?></td>
		</tr>


		<?php $i++;
	endforeach ?>
</table>
<?= $orders->links() ?>
<div id="message" class="text-center"></div>
