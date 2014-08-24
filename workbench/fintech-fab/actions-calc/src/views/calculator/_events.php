<?php
/**
 * File _events.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Event[] $events
 */
?>

<!-- buttons: search and add event -->
<div class="row">
	<div class="large-3 columns">
		<div class="row collapse postfix">
			<div class="large-9 columns">
				<input type="text" placeholder="Искать в базе">
			</div>
			<div class="large-3 columns">
				<a href="#" class="button postfix">Go</a>
			</div>
		</div>
	</div>
	<div class="large-6 columns">
		<a data-reveal-id="manage-modal" id="event-create" href="#" class="button small right"> Добавить событие
			<i class="fi-plus"></i></a>
	</div>
</div><!-- /buttons: search and add event -->

<!-- modal create event -->
<div id="manage-modal" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::event.create'); ?>
</div><!-- /modal create event-->

<!-- events table -->
<div id="events-table-container">
	<?php $events->setBaseUrl('events/table'); ?>
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<script type="text/javascript">
	$(document).ready(function () {

		// pagination through ajax
		$('body').on('click', 'ul.pagination a', function (e) {
			e.preventDefault();
			console.log($(this).attr('href'));

			var $eventTableContainer = $('#events-table-container');

			$.get($(this).attr('href'),
				{},
				function (oData) {
					$eventTableContainer.html(oData);
				},
				'html'
			);

			return false;
		});

		// modal event create
		$('#manage-modal').submit(function (e) {
			e.preventDefault();

			// $th form create event
			var $th = $(this);
			var $submit = $th.find('#button-event-create');

			buttonSleep($submit);

			$.post('/actions-calc/event/create',
				$th.find('form').serialize(),
				function (oData) {
					if (oData.status == 'success') {
						$('#manage-modal').foundation('reveal', 'close');
						updateEventsTable();
						clearFormErrors($th);
					} else if (oData.status == 'error') {
						revealFormErrors($th, oData.errors);
					}

					buttonWakeUp($submit);
					return false;
				},
				'json'
			);
		});

		// toggle event rules flag
		$(document).on('click', '.switch label', function () {
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

		// see event rules
		$(document).on('click', '.see-rules', function (e) {
			e.preventDefault();
			// $th clicked button "see rules"
			var $th = $(this);
			var $parentTr = $th.closest('tr');
			var iTdCount = $parentTr.children('td').length;

			// no rules = no moves, also not doing anything while button disabled
			if (+$th.data('rules-count') < 1 || buttonBusy($th)) {
				return false;
			}

			// opening event rules
			// loading rules makes sense once
			if ($th.hasClass('rules-loaded') == false) {
				// disabling button and preventing click while loading rules
				buttonSleep($th);
				// ajax sending
				$.post('/actions-calc/manage/get-event-rules',
					{event_id: $parentTr.data('id')},
					function (oData) { // success function

						$("<tr data-event-rules-" + $parentTr.data('id') + ">" +
						"<td colspan=" + iTdCount + ">" + oData + "</td></tr>").insertAfter($parentTr);

						buttonWakeUp($th);

						$th.addClass('rules-loaded');
						// make visible "close" button and hiding self
						$th.hide();
						$th.next('a.close-rules').show();
					},
					'html'
				);
			} else {
				// if rules loaded, just showing them and toggling see-rules\close-rules buttons
				$th.hide();
				$th.next('a.close-rules').show();
				$(this).closest('tr').next('tr').show();
			}
		});

		// close rules button
		$(document).on('click', 'a.close-rules', function(e) {
			e.preventDefault();
			console.log($(this));
			$(this).hide();
			$(this).prev('a.see-rules').show();
			$(this).closest('tr').next('tr').hide();
		});

	});

	// toggling "close button", showing opposite button
	// and hiding rules table
	$('#events-table-container').on('click', 'a.close-rules', function () {
		var $th = $(this);

		$th.toggle();
		$th.prev('a.see-rules').show();
		$th.closest('tr').next('tr').hide();
	});

	/**
	 * Update event table
	 *
	 * @returns {boolean}
	 */
	function updateEventsTable() {
		var $eventTableContainer = $('#events-table-container');

		$.get('/actions-calc/manage/update-events-table',
			{},
			function (oData) {
				$eventTableContainer.html(oData);
			},
			'html'
		);

		return false;
	}

	/**
	 * Get url parameters
	 *
	 * example: var page = getUrlParameter('page');
	 *
	 * @param sParam
	 * @returns {*}
	 */
	function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) {
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) {
				return sParameterName[1];
			}
		}
	}

	/**
	 * Reveal errors in a form, if validation on server fails
	 *
	 * @param form
	 * @param errors
	 */
	function revealFormErrors(form, errors) {
		clearFormErrors(form);
		$.each(errors, function (sName, message) {
			form.find('label[for="' + sName + '"]').addClass('error');
			form.find('input[name="' + sName + '"]').addClass('error');
			form.find('small[id="' + sName + '-error"]').addClass('error');
		});
	}

	/**
	 * Clear form errors
	 *
	 * @param form
	 */
	function clearFormErrors(form) {
		form.find('label').removeClass('error');
		form.find('input').removeClass('error');
		form.find('small').removeClass('error');
	}

	/**
	 * Button becomes available again
	 *
	 * @param button
	 */
	function buttonWakeUp(button) {
		button.removeAttr('disabled');
		$('body').off('click', button);
	}

	/**
	 * Blocking button
	 *
	 * @param button
	 */
	function buttonSleep(button) {
		button.attr('disabled', 'disabled');
		$('body').on('click', button, function () {
			var bIsButtonDisabled = !!button.attr('disabled');
			return !bIsButtonDisabled;
		});
	}

	/**
	 * Evaluate, if is button available
	 *
	 * @param button
	 * @returns {boolean}
	 */
	function buttonBusy(button) {
		return !!button.attr('disabled');
	}

</script>