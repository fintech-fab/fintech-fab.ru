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
		<ul class="button-group right">
			<li>
				<a id="manage-chain" href="#" class="button secondary small right">
					Цепочкой&nbsp;<i class="fi-plus"></i>&nbsp;<i class="fi-plus"></i></a>
			</li>
			<li><a data-reveal-id="manage-modal" id="event-create" href="#" class="button small right"> Добавить событие
					<i class="fi-plus"></i></a>
			</li>
		</ul>
	</div>
</div><!-- /buttons: search and add event -->

<!-- modal create event -->
<div id="manage-modal" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::event.create'); ?>
</div><!-- /modal create event-->

<!-- modal update event -->
<div id="modal-update-event" class="reveal-modal small" data-reveal></div><!-- /modal update event-->

<!-- modal rule update -->
<div id="modal-rule-update" class="reveal-modal small" data-reveal></div><!-- /modal rule update -->

<!-- events table -->
<div id="events-table-container">
	<?php /** @noinspection PhpUndefinedMethodInspection */
	$events->setBaseUrl('events/table'); ?>
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<script type="text/javascript">
$(document).ready(function () {

	// events:
	// events table pagination
	// pagination through ajax
	$(document).on('click', 'ul.pagination a', function (e) {
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

	// events:
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

	// events:
	// event udpate open
	$(document).on('click', 'a.edit-rule', function (e) {
		e.preventDefault();

		var $th = $(this);
		var $eventId = $th.closest('tr').data('id');

		buttonSleep($th);
		$.get('/actions-calc/event/update/' + $eventId,
			function (oData) {
				// showing update form
				$('#modal-update-event').html(oData).foundation('reveal', 'open');
				buttonWakeUp($th);
			},
			'html'
		).always(function () {
				buttonWakeUp($th);
			});
	});
	// events:
	// event udpate update
	$(document).on('click', '#button-event-update', function (e) {
		e.preventDefault();

		var $th = $(this);
		var $eventId = $th.closest('form').data('id');

		buttonSleep($th);
		$.post(
			'/actions-calc/event/update/' + $eventId,
			$th.closest('form').serialize(),
			function (oData) {
				if (oData.status == 'success') {
					$('#modal-update-event').foundation('reveal', 'close');
					console.log(oData);
					updateTableRow(oData.update);
					clearFormErrors($th);
				} else if (oData.status == 'error') {
					revealFormErrors($th.closest('form'), oData.errors);
				}
			},
			'json'
		).always(function () {
				buttonWakeUp($th);
			});

		return false;
	});

	// events:
	// modal event delete
	$(document).on('click', '#events-rules a.delete-rule', function (e) {
		e.preventDefault();

		// $th clicked close button
		var $th = $(this);
		buttonSleep($th);

		var $thisRow = $th.closest('tr');
		var $eventId = $thisRow.data('id');
		var $nextRow = $thisRow.next('tr.event-rules-row');
		var $nextRowId = $nextRow.data('event-rules');

		$.post('/actions-calc/event/delete',
			{id: $eventId},
			function (oData) {
				if (oData.status == 'success') {
					// deleted, removing table records and opened rules, if exists
					if ($nextRowId == $eventId) {
						$nextRow.fadeOut();
					}
					$thisRow.fadeOut();
				}
				buttonWakeUp($th);
				return false;
			},
			'json'
		);

		return false;
	});

	// event -> rules:
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

	// events -> rules:
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

					// placing event rules in ceparate table, and showing
					$("<tr class='event-rules-row' data-event-rules=" + $parentTr.data('id') + ">" +
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

	// events -> rules:
	// add rule button // TODO: add rule.
	$(document).on('click', 'a.close-rules', function (e) {
		e.preventDefault();
		$('#modal-rule-add').foundation('reveal', 'open');
	});

	// events -> rules:
	// rule update button
	$(document).on('click', 'a.rule-update', function (e) {
		e.preventDefault();

		var $th = $(this);
		var $ruleId = $th.closest('tr').data('id');

		buttonSleep($th);

		$.get(
			'/actions-calc/rule/update/' + $ruleId,
			$th.closest('form').serialize(),
			function (oData) {
				$('#modal-rule-update').html(oData).foundation('reveal', 'open');
				var $sRule = $('#modal-rule-update').find('form > input[name="rule"]').val();

				// TODO: this for update.
				var $oRule = $.parseJSON($sRule);
				console.log($oRule);
				$.each($oRule, function (index, rule) {
					console.log(rule.name + ' ' + rule.operator);
				});
			},
			'html'
		).always(function () {
				buttonWakeUp($th);
			});

		return false;

	});

	// events -> rules:
	// close rules button
	$(document).on('click', 'a.close-rules', function (e) {
		e.preventDefault();

		$(this).hide();
		$(this).prev('a.see-rules').show();
		$(this).closest('tr').next('tr').hide();
	});

});

var rulesFactory = function () {

//	const OP_BOOL = 'bool';
//	const OP_GREATER = 'greater';
//	const OP_GREATER_OR_EQUAL = 'greaterOrEqaul';
//	const OP_LESS = 'less';
//	const OP_LESS_OR_EQUAL = 'lessOrEqual';
//	const OP_EQUAL = 'equal';
//	const OP_NOT_EQUAL = 'notEqual';

	this.placeOn = function (sSelector) {
		var $container = $(sSelector);
	};

	this.buildByJson = function () {

	};

	this.operators = [
		{'bool': 'OP_BOOL'},
		{'>': 'OP_GREATER'},
		{'>=': 'OP_GREATER_OR_EQUAL'},
		{'<': 'OP_LESS'},
		{'<=': 'OP_LESS_OR_EQUAL'},
		{'=': 'OP_EQUAL'},
		{'!=': 'OP_NOT_EQUAL'}
	];

	// container :: rules container :: input\selects
	// input\selects model:
	// - 1st [key]-[value]-[operator^},
	// - 2nd [rule_operator]-[key]-[value]-[operator^}
	this.settings = {
	};

};

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
 * Update table row
 * @param oData
 */
function updateTableRow(oData) {
	var $row = $('tr[data-id="' + oData.id + '"]');
	$row.find('td.event-name').html(oData.name);
	$row.find('td.event-sid').html(oData.event_sid);
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