<?php
/**
 * File _events.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Event[] $events
 */
?>

<a data-reveal-id="manage-modal" id="event-create" href="#" class="button small right"> Добавить событие
	<i class="fi-plus"></i> </a>

<!-- modal create event -->
<div id="manage-modal" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::event.create'); ?>
</div><!-- /modal create event-->

<!-- events table -->
<div id="events-table-container">
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<script type="text/javascript">
	$(document).ready(function () {

		// modal event create
		$('#manage-modal').on('click', '#button-event-create', function (e) {
			e.preventDefault();

			var $th = $(this);

			$.post('/actions-calc/event/create',
				$th.closest('form').serialize(),
				function (oData) {
					if (oData.status == 'success') {
						$('#manage-modal').foundation('reveal', 'close');
						updateEventsTable();
					} else {
						$('#manage-modal').html(oData); // TODO: handle json errors
					}
				},
				'json'
			);

			return false;
		});

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
				$.post('/actions-calc/manage/get-event-rules',
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

	function activateCloseButton($th) {
		$th.next('a.close-rules').click(function () {
			$(this).toggle();
			$th.toggle();
			$th.closest('tr').next('tr').toggle();
		});
	}

	function updateEventsTable() {
		var $eventTableContainer = $('#events-table-container');

		$.get('/actions-calc/manage/events-table-update',
			{},
			function (oData) {
				$eventTableContainer.html(oData);
			},
			'html'
		);

		return false;
	}
</script>