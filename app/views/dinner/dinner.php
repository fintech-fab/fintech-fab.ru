<?php

// Если $end_dinner не определена по умолчанию false;
$end_dinner = empty($end_dinner) ? false : $end_dinner;


if(!$end_dinner):

	// Если $end_dinner === false отрисовываем табличку для заказа обеда?>

	<table class="table table-hover">
		<thead>
			<td>Название блюда</td>
			<td>Описание Блюда</td>
			<td>Цена</td>
			<td>Заказ</td>
		</thead>

	<?php foreach($menu as $food):?>
		<tr id="this-food-id-<?=$food->id?>">
			<td><?=$food->title?></td>
			<td><?=$food->description?></td>
			<td><?=$food->price?></td>
			<td><input type="text"/></td>
		</tr>
	<?php endforeach;?>

	</table>

<?php else:

	// Если $end_dinner === true значит заказ обедов окончен ?>

	<div id="end-dinner">
		<p>Извините , в данное время сделать заказ невозможно!.</p>
		<p onclick=" window.history.back() ">Назад</p>
	</div>

<?php endif;?>