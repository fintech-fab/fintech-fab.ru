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
						<a data-rules-count="<?php echo $event->rules->count(); ?>" href="#" class="tiny button see-rules">
							<?php echo $event->rules->count(); ?>&nbsp;<i class="fi-eye"></i> </a>
						<a href="#" class="tiny button close-rules" style="display: none;">закрыть</a>
					</li>
					<li><a href="#" class="tiny button success"><i class="fi-plus"></i></a></li>
				</ul>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<script type="text/javascript">
	$(document).ready(function () {

		function activateCloseButton($th) {
			$th.next('a.close-rules').click(function() {
				$(this).toggle();
				$th.toggle();
				$th.closest('tr').next('tr').toggle();
			});
		}


		$('.see-rules').click(function () {

			var $th = $(this);
			var $parentTr = $th.closest('tr');
			var iTdCount = $parentTr.children('td').length;

			if($th.hasClass('rules-loaded') == false) {
				$.post(
					'/actions-calc/manage/get-event-rules',
					{event_id: $parentTr.data('id')},
					function (oData, sStatus) {
						$("<tr><td colspan=" + iTdCount + ">" + oData + "</td></tr>").insertAfter($parentTr);
						activateCloseButton($th);
						$th.addClass('rules-loaded');
						// make visible "close" button
						$th.toggle();
						$th.next('a').toggle();
					},
					'html'
				);
			} else {
				$th.toggle();
				$th.next('a').toggle(); // TODO: close open.
			}
		});
	});
</script>