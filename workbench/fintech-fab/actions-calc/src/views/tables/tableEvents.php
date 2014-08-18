<?php


use FintechFab\ActionsCalc\Models\Event;

/**
 * @var Event $events
 * @var Event $event
 */

?>
<style type="text/css">
	<?php require(__DIR__ . '/../layouts/inc/css/styleForTableRules.css') ?>
</style>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/EditTableEvents.js') ?>
</script>
<?= View::make('ff-actions-calc::tables.inc.TableEvents.changeDataEventModal') ?>

<?= View::make('ff-actions-calc::tables.inc.TableEvents.addDataEventModal') ?>

<?= View::make('ff-actions-calc::tables.inc.TableEvents.addDataRuleModal') ?>

<?=
Form::button('Добавить событие', array(
	'class'       => 'btn btn-sm btn-info tableBtn tableAddBtn',
	'data-toggle' => 'modal',
	'data-target' => '#addDataEventTable',
));
echo '<br><br>'
?>
<div class="bb-alert alert alert-info" style="display:none;">
	<span></span>
</div>
<table class="table table-striped table-hover" id="ordersTable">
	<tr>
		<td><b>ID</b></td>
		<td><b>Название</b></td>
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
					'data-event'  => $event,
					'data-toggle' => 'modal',
					'data-target' => '#changeDataModal',
				)) ?>
				<?=
				Form::button('Правила', array(
					'class'   => 'btn btn-sm btn-info tableBtn tableGetRules',
					'data-id' => $event->id,
				))?>
				<?=
				Form::button('Добавить правило', array(
					'class'       => 'btn btn-sm btn-info tableBtn addRule',
					'data-toggle' => 'modal',
					'data-target' => '#addDataRuleModal',
					'data-id'     => $event->id,
				))?>
				<button type="button" class="btn btn-sm btn-info tableBtn refreshRules" data-toggle="modal" disabled="disabled" data-id="<?= $event->id ?>">
					<span class="glyphicon glyphicon-refresh"></span>
				</button>

			</td>
		</tr>
	<?php endforeach ?>
</table>
<div id="content"></div>
<?= $events->links() ?>
<div id="message" class="text-center"></div>
