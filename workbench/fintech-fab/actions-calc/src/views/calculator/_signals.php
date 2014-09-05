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
					</li>
					<li>
						<button class="tiny button alert signal-delete">&nbsp;<i class="fi-x"></i></button>
					</li>
				</ul>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
