<?php
/**
 * File _event_rules.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Rule[] $rules
 */
?>
<div id="event-signals-wrap">
	<table id="event-signals" width="100%">
		<thead>
		<tr>
			<th width="200">sid</th>
			<th>Имя</th>
			<th width="200" class="text-center">Правила</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($rules as $rule): ?>
			<tr data-id="<?php echo $rule->id ?>">
				<td><?php echo $rule->signal_sid ?></td>
				<td><?php echo $rule->name; ?></td>
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