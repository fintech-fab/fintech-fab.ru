<?php
/**
 * File _events.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Event[] $events
 */
?>

<table width="100%">
	<thead>
	<tr>
		<th width="200">sid</th>
		<th>Имя</th>
		<th width="200" class="text-center">Правила</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($events as $event): ?>
		<tr data-id="<?php echo $event->id; ?>">
			<td><?php echo $event->event_sid ?></td>
			<td><?php echo $event->name; ?></td>
			<td>
				<ul class="event-buttons button-group right">
					<li>
						<a href="#" class="tiny button">
							<?php echo $event->rules->count();?>&nbsp;<i class="fi-eye see-rules"></i>
						</a>
					</li>
					<li><a href="#" class="tiny button success"><i class="fi-plus"></i></a></li>
				</ul>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<style type="text/css">
.event-buttons li a {
	margin-bottom: 0;
}
</style>

<script type="text/javascript">
	$('.see-rules').click(function() {

	});
</script>