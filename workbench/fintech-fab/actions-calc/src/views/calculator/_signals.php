<?php
/**
 * File _signals.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Signal[] $signals
 */
?>
<div id="manage-signals-wrap">
	<a id="signal-add" href="#" class="button small right">Добавить сигнад <i class="fi-plus"></i></a>
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
			<tr data-id="<?php echo $signal->id ?>">
				<td><?php echo $signal->signal_sid ?></td>
				<td><?php echo $signal->name; ?></td>
				<td>
					<ul class="signal-buttons button-group right">
						<li><a href="#" class="tiny button">&nbsp;<i class="fi-page-edit"></i></a></li>
					</ul>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
