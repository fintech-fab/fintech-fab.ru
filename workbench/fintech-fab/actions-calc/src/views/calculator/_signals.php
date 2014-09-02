<?php
/**
 * File _signals.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Signal[] $signals
 */
?>

<div class="row">
	<div class="large-12 columns">
		<button class="button small right" data-reveal-id="modal-signal-create">
			Добавить сигнал&nbsp;<i class="fi-plus"></i>
		</button>
	</div>
</div>

<table id="manage-signals" width="100%">
	<thead>
	<tr>
		<th width="200">sid</th>
		<th>Имя</th>
		<th width="200" class="text-center">Правила</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($signals as $signal): ?>
		<tr id="<?php echo $signal->id ?>">
			<td class="signal_sid"><?php echo $signal->signal_sid ?></td>
			<td class="signal_name"><?php echo $signal->name; ?></td>
			<td>
				<ul class="signal-buttons button-group right">
					<li>
						<button class="tiny button signal-edit">&nbsp;<i class="fi-page-edit"></i></button>
						<button class="tiny button alert signal-delete">&nbsp;<i class="fi-x"></i></button>
					</li>
				</ul>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<script type="text/javascript">
	//	$(document).ready(function () {

	//		$('#signal-add').click(function () {
	//
	//			oSignalRow[0] = 'new signal';
	//			oSignalRow[1] = 'Новый сигнал';
	//
	//			$signalsTable.row.add(oSignalRow).draw().node();
	//
	//			return false;
	//		});

	//		$('#signal-add-end').click(function () {
	//
	//			$signalsTable.row.add([
	//				'zabigale',
	//				'яа тест таблицы',
	//				oButtons.edit
	//			]).draw().node();
	//
	//			return false;
	//		});
	//
	//		$('#signal-search').click(function () {
	//			var response = {
	//				event_sid: "response sid",
	//				event_name: "response name"
	//			};
	//
	//			oSignalRow[0] = response.event_sid;
	//			oSignalRow[1] = response.event_name;
	//
	//			$signalsTable.row('[data-id="2"]').data(oSignalRow).draw();
	//
	//			return false;
	//		});

	//	});
</script>