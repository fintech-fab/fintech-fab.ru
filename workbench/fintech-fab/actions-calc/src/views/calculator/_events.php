<?php
/**
 * File _events.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Event[] $events
 */
?>
<a id="event-create" href="#" class="button small right">Добавить событие <i class="fi-plus"></i></a>
<table id="events-rules" width="100%">
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

<!-- pagination -->
<?php if (method_exists($events, 'links')): ?>
	<?php echo $events->links(); ?>
<?php endif; ?>
<!-- /pagination -->

<!-- modal -->
<div id="manage-modal" class="reveal-modal" data-reveal>
	<?php
//	echo Form::open([
//		'url' => 'EventController@create'
//	]);

	echo Form::open();
	echo Form::text('name');
	echo Form::submit('Добавить', ['class' => 'button small']);
	?>
</div>
<!-- /modal -->

<script type="text/javascript">
	$(document).ready(function () {

		// toggle event rules flag
		$('#events-rules').on('click', '.switch label', function () {
			var $iRuleId = $(this).closest('tr').data('id');
			var $bFlagActive = !!$(this).prev('input').attr('checked');
			var bSwitchResult = true;

			$.ajax({
				type: 'POST',
				url: '/actions-calc/manage/toggle-rule-flag',
				data: {id: $iRuleId, flag_active: !$bFlagActive},
				success: function (oData) {
					if (oData.status == 'error') {
						bSwitchResult = false;
					}
				},
				dataType: 'json',
				async: false
			});

			return bSwitchResult;
		});

		function activateCloseButton($th) {
			$th.next('a.close-rules').click(function () {
				$(this).toggle();
				$th.toggle();
				$th.closest('tr').next('tr').toggle();
			});
		}

		$('.see-rules').click(function () {
			// $th clicked button "see rules"
			var $th = $(this);
			var $parentTr = $th.closest('tr');
			var iTdCount = $parentTr.children('td').length;

			// no rules = no moves, also not doing anything while button disabled
			if (+$th.data('rules-count') < 1 || !!$th.attr('disabled')) {
				return false;
			}

			// opening event rules
			// loading rules makes sense once
			if ($th.hasClass('rules-loaded') == false) {
				// disabling button and preventing click while loading rules
				$th.attr('disabled', 'disabled');
				// ajax sending
				$.post(
					'/actions-calc/manage/get-event-rules',
					{event_id: $parentTr.data('id')},
					function (oData, sStatus) { // success function

						$("<tr data-event-rules-" + $parentTr.data('id') + ">" +
						"<td colspan=" + iTdCount + ">" + oData + "</td></tr>").insertAfter($parentTr);

						activateCloseButton($th);

						$th.removeAttr('disabled');
						$th.addClass('rules-loaded');
						// make visible "close" button
						$th.toggle();
						$th.next('a').toggle();
					},
					'html'
				);
			} else {
				$th.toggle();
				$th.next('a').toggle();
				$th.closest('tr').next('tr').toggle();
			}
		});
	});

	$('#event-create').click(function(e) {
		e.preventDefault();
		$('#manage-modal').foundation('reveal', 'open');
	});

</script>