<?php

use FintechFab\ActionsCalc\Models\Rule;

/**
 * @var Rule $rules
 * @var Rule $rule
 */
?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/EditTableRulesForEvent.js') ?>
</script>
<?= View::make('ff-actions-calc::tables.inc.TableEvents.changeDataRuleModal', array('rule' => $rules)) ?>


<?= '<br>' ?>
<table class="table table-striped table-hover" id="ordersTable" style='border-bottom: 2px solid black; border-left: 2px solid black;'>
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
				Form::checkbox('active', '', $rule->flag_active, array(
					'class' => 'checkbox',
					'id'    => $rule->id,
				)) ?>
			</td>
			<td>
				<?=
				Form::button('Изменить правило', array(
					'class'       => 'btn btn-sm btn-info btnEdit',
					'data-action' => 'showStatus',
					'data-rule'   => $rule,
					'data-toggle' => 'modal',
					'data-target' => '#changeDataRuleModal',
				)) ?>
			</td>
		</tr>


	<?php endforeach ?>

</table>
<?= $rules->links() ?>
<div id="message" class="text-center"></div>
