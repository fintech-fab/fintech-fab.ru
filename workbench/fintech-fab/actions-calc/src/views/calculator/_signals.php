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
						<li><a href="#" class="tiny button">&nbsp;<i class=""></i></a></li>
					</ul>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<style type="text/css">
	.signal-buttons li a {
		margin-bottom: 0;
	}

	#manage-signals-wrap {
		max-height: 720px;
		overflow: scroll;
		overflow-x: hidden;
	}
</style>
