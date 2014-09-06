<?php

// Если $end_dinner не определена по умолчанию false;
$end_dinner = empty($end_dinner) ? false : $end_dinner;
// Пока у нас один юзер
$user_id = 5;

if(!$end_dinner):

	// Если $end_dinner === false отрисовываем табличку для заказа обеда?>

	<h3>Сделать заказ</h3>

	<table class="table table-hover">

		<thead>
			<td class="first">Название блюда</td>
			<td>Описание Блюда</td>
			<td>Цена</td>
			<td>Заказ</td>
		</thead>

	<?php foreach($menu as $food):?>

		<tr id="this-food-id-<?=$food->id?>">
			<td class="first"><?=$food->title?></td>
			<td><?=$food->description?></td>
			<td><?=$food->price?></td>
			<td>
				<span class="less glyphicon glyphicon-minus-sign"></span>&nbsp;
				<span class="quntity">0</span>&nbsp;
				<span class="more glyphicon glyphicon-plus-sign"></span>
			</td>
		</tr>

	<?php endforeach;?>

	</table>

	<input type="hidden" id="user-id" value="<?=$user_id?>" />

<?php else:

	// Если $end_dinner === true значит заказ обедов окончен ?>

	<div id="end-dinner">
		<h3>Недоступно</h3>
		<div>Заказ обеда можно сделать в период с 8.00 до 16.00 .<br/>
			Текущие время : <span id="show-time"></span>
		</div>
		<br/>
		<div>
			<button type="button" class="btn btn-danger" onclick="window.history.back()">Назад</button>
		</div>
	</div>

<?php endif;?>