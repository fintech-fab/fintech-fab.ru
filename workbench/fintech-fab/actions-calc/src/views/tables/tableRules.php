<?php

use FintechFab\ActionsCalc\Models\Rule;

/**
 * @var Rule $rules
 * @var Rule $rule
 */

?>
<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Правило</b></td>
		<td><b>Событие</b></td>
		<td><b>Условие</b></td>
		<td><b>Сигнал</b></td>
		<td><b>Активно?</b></td>
		<td><b>Действия</b></td>
	</tr>
	<?php foreach ($rules as $rule): ?>
		<tr>
			<td><?= $rule->id ?></td>
			<td><?= $rule->name ?></td>
			<td><?= $rule->event->event_sid ?></td>
			<td><?= $rule->rule ?></td>
			<td><?= $rule->signal->signal_sid ?></td>
			<td>
				<?=
				Form::checkbox('active', $rule->flag_active, array(
					'class' => 'checkbox',
				)) ?>
			</td>
			<td>
				<?=
				Form::button('Изменить', array(
					'class'       => 'btn btn-sm btn-info tableBtn',
					'data-action' => 'showStatus',
					'data-id'     => $rule->id,
				)) ?>
			</td>
		</tr>

	<?php endforeach ?>
</table>
<?= $rules->links() ?>
<div id="message" class="text-center"></div>
