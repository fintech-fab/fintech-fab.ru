<?php

use FintechFab\ActionsCalc\Models\Rule;

/**
 * @var Rule $rules
 * @var Rule $rule
 */

?>
<style type="text/css">
	<?php require(__DIR__ . '/../layouts/inc/css/styleForTableRules.css') ?>
</style>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/EditTableRule.js') ?>
</script>
<?= View::make('ff-actions-calc::tables.inc.TableRule.changeDataRuleModal', array('rule' => $rules)) ?>

<?= View::make('ff-actions-calc::tables.inc.TableRule.addDataRuleModal') ?>


<?=
Form::button('Добавить правило', array(
	'class'       => 'btn btn-sm btn-info tableBtn tableAddBtn',
	'data-toggle' => 'modal',
	'data-target' => '#addDataRuleModal',
));
echo '<br><br>'
?>
<div class="bb-alert alert alert-info" style="display:none;">
	<span></span>
</div>
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
				Form::checkbox('active', '', $rule->flag_active, array(
					'class' => 'checkbox',
					'id'    => $rule->id,
				)) ?>
			</td>
			<td>
				<?=
				Form::button('Изменить', array(
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
