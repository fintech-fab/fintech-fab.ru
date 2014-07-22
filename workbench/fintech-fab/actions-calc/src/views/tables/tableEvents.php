<?php

use FintechFab\ActionsCalc\Models\Rule;

/**
 * @var Rule $events
 * @var Rule $event
 */
?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/EditTableEvents.js') ?>
</script>
<?= View::make('ff-actions-calc::tables.inc.changeDataModal') ?>

<?= View::make('ff-actions-calc::tables.inc.addDataEventTable') ?>


<?=
Form::button('Добавить', array(
	'class'       => 'btn btn-sm btn-info tableBtn tableAddBtn',
	'data-toggle' => 'modal',
	'data-target' => '#addDataEventTable',
));
echo '<br><br>'
?>


<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Правило</b></td>
		<td><b>Событие</b></td>
		<td><b>Действия</b></td>
	</tr>
	<?php
	foreach ($events as $event): ?>
		<tr>
			<td><?= $event->id ?></td>
			<td><?= $event->name ?></td>
			<td><?= $event->event_sid ?></td>
			<td>
				<?=
				Form::button('Изменить', array(
					'class'       => 'btn btn-sm btn-info tableBtn tableEditBtn',
					'data-action' => 'showStatus',
					'data-id'     => $event->id,
					'data-toggle' => 'modal',
					'data-target' => '#changeDataModal',
				)) ?>
			</td>
		</tr>

	<?php endforeach ?>
</table>
<?= $events->links() ?>
<div id="message" class="text-center"></div>
