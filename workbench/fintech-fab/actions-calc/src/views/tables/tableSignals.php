<?php


use FintechFab\ActionsCalc\Models\Signal;

/**
 * @var Signal $signals
 * @var Signal $signal
 */

?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/EditTableSignals.js') ?>
</script>
<?= View::make('ff-actions-calc::tables.inc.TableSignals.changeDataSignalModal') ?>

<?= View::make('ff-actions-calc::tables.inc.TableSignals.addDataSignalModal') ?>


<?=
Form::button('Добавить', array(
	'class'       => 'btn btn-sm btn-info tableBtn tableAddBtn',
	'data-toggle' => 'modal',
	'data-target' => '#addDataSignalModal',
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
	foreach ($signals as $signal): ?>
		<tr>
			<td><?= $signal->id ?></td>
			<td><?= $signal->name ?></td>
			<td><?= $signal->signal_sid ?></td>
			<td>
				<?=
				Form::button('Изменить', array(
					'class'       => 'btn btn-sm btn-info tableBtn tableEditBtn',
					'data-action' => 'showStatus',
					'data-signal' => $signal,
					'data-toggle' => 'modal',
					'data-target' => '#changeDataModal',
				)) ?>
			</td>
		</tr>

	<?php endforeach ?>
</table>
<?= $signals->links() ?>
<div id="message" class="text-center"></div>
