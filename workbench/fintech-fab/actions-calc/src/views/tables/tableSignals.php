<?php

use FintechFab\ActionsCalc\Models\Rule;

/**
 * @var Rule $signal
 * @var Rule $signals
 */

?>
<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Правило</b></td>
		<td><b>Событие</b></td>
		<td><b>Действия</b></td>
	</tr>
	<?php
	foreach ($signals as $signal): ?>
		<tr>
			<td><?= $signal->id ?></td>
			<td><?= $signal->name ?></td>
			<td><?= $signal->signal_sid ?></td>
			<td>
				<?=
				Form::button('Изменить', array(
					'class'       => 'btn btn-sm btn-info tableBtn',
					'data-action' => 'showStatus',
					'data-id'     => $signal->id,
				)) ?>
			</td>
		</tr>

	<?php endforeach ?>
</table>
<?= $signals->links() ?>
<div id="message" class="text-center"></div>
