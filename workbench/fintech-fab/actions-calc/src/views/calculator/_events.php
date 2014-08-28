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
<div id="modal-rule-update" class="reveal-modal medium" data-reveal></div><!-- /modal rule update -->

<!-- events table -->
<div id="events-table-container">
	<?php /** @noinspection PhpUndefinedMethodInspection */
	$events->setBaseUrl('events/table'); ?>
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<!-- event rules template -->
<div id="event-rules-template" style="display: none;">
	<div class="event-rule">
		<div class="large-4 columns">
			<input class="event-rule-name" name="event-rule-name" type="text" placeholder="имя">
		</div>
		<div class="large-2 columns">
			<select class="event-rule-operator" name="event-rule-operator">
				<option value="OP_BOOL">bool</option>
				<option value="OP_GREATER">></option>
				<option value="OP_GREATER_OR_EQUAL">>=</option>
				<option value="OP_LESS"><</option>
				<option value="OP_LESS_OR_EQUAL"><=</option>
				<option value="OP_EQUAL">=</option>
				<option value="OP_NOT_EQUAL">!=</option>
			</select>
		</div>
		<div class="large-4 columns event-rule-value-wrap">
			<input class="event-rule-value" name="event-rule-value" type="text" placeholder="значение">
		</div>
		<div class="large-2 columns">
			<a href="#" class="button secondary tiny delete-event-rule"><i class="fi-x"></i></a>
		</div>
	</div>
	<div class="rule-condition-bool">
		<select class="condition-bool">
			<option value="true">true</option>
			<option value="false">false</option>
		</select>
	</div>
</div><!-- /event rules template -->


<script type="text/javascript">
$(document).ready(function () {

	var $body = $('body');

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
	// event udpate modal - open
	$(document).on('click', 'button.edit-rule', function () {
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
	// event udpate modal - update
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
					updateRuleRow(oData.update);
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
	$body.on('click', '#events-rules button.delete-rule', function () {
		// $th clicked delete button
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
	$(document).on('click', '#event-rules-wrap div.switch label', function () {
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
	$body.on('click', 'button.see-rules', function () {
		// $th clicked button "see rules"
		var $th = $(this);
		var $parentTr = $th.closest('tr');
		var iTdCount = $parentTr.children('td').length;

		// no rules = no moves, also not doing anything while button disabled
		if ($th.data('rules-count') == 0 || buttonBusy($th)) {
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
					$th.next('button.close-rules').show();
				},
				'html'
			);
		} else {
			// if rules loaded, just showing them and toggling see-rules\close-rules buttons
			$th.hide();
			$th.next('button.close-rules').show();
			$(this).closest('tr').next('tr').show();
		}
	});

	// events -> rules:
	// add rule button // TODO: add rule.
	$(document).on('click', 'button.close-rules', function (e) {
		e.preventDefault();
		$('#modal-rule-add').foundation('reveal', 'open');
	});

	// events -> rules:
	// rule update button - open
	$body.on('click', 'button.rule-update', function () {

		var $th = $(this);
		var $ruleId = $th.closest('tr').data('id');

		buttonSleep($th);
		$.get(
			'/actions-calc/rule/update/' + $ruleId,
			$th.closest('form').serialize(),
			function (oData) {
				$('#modal-rule-update').html(oData).foundation('reveal', 'open');

				var $sRule = $('#modal-rule-update').find('input[name="rule"]').val();
				var rulesFactory = new RulesFactory();
				rulesFactory.formFromJson($sRule);
			},
			'html'
		).always(function () {
				buttonWakeUp($th);
			});

		return false;
	});
	// events -> rules:
	// rule update button - update
	// forming JSON string from rule conditions
	$(document).on('click', '#button-rule-update', function (e) {
		e.preventDefault();

		// finding container with rule conditions
		var $th = $(this);
		var $rulesContainer = $('#event-rules-translate');
		var aoRuleData = [];
		var $aRules = $rulesContainer.find('div.event-rule');

		// forming condition objects
		$.each($aRules, function (index, rule) {

			var sRuleOperator = $(rule).find('select.event-rule-operator > option:selected').val();
			// checking if different input types input|select
			var ruleValue = (sRuleOperator == 'OP_BOOL') ? $(rule).find('select.condition-bool > option:selected').val() :
				$(rule).find('input.event-rule-value').val();

			ruleValue = typeFromString(ruleValue);
			ruleValue = (ruleValue === undefined || ruleValue == "undefined") ? !!ruleValue : ruleValue;

			aoRuleData.push({
				name: $(rule).find('input.event-rule-name').val(),
				value: ruleValue,
				operator: sRuleOperator
			});
		});

		console.log('object aoRuleData');
		console.log(aoRuleData);

		var sRules = JSON.stringify(aoRuleData);
		console.log('stringify sRules');
		console.log(sRules);

		// updating hidden input with rules
		$th.closest('form').find('input[name="rule"]').val(sRules);

		var $ruleId = $th.closest('form').data('id');

		// update rule request
		buttonSleep($th);
		$.post(
			'/actions-calc/rule/update/' + $ruleId,
			$th.closest('form').serialize(),
			function (oData) {
				if (oData.status == 'success') {
					$('#modal-rule-update').foundation('reveal', 'close');
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
	// events -> rules:
	// rule delete button - delete
	$body.on('click', 'button.rule-delete', function () {

		// $th clicked delete button
		var $th = $(this);
		buttonSleep($th);

		var $thisRow = $th.closest('tr');
		var $ruleId = $thisRow.data('id');

		$.post('/actions-calc/rule/delete/' + $ruleId,
			function (oData) {
				if (oData.status == 'success') { // success
					// update event -> rules button counter
					updateRulesCounter($th, oData);
					console.log($('button.see-rules').data('rules-count'));
					// deleted, removing table records and opened rules, if exists
					$thisRow.fadeOut();
					// closing rules table if 0 rules
					if (oData.data.count <= 0) {
						var iParentEventId = $th.parents('tr.event-rules-row').data('event-rules');
						$('#events-rules').find('tr[data-id=' + iParentEventId + ']').find('button.close-rules').click();
					}
				} else if (oData.error) {
					alert(oData.error);
				}
				buttonWakeUp($th);
				return false;
			},
			'json'
		).always(function () {
				buttonWakeUp($th);
			});

		return false;
	});

	// events -> rules:
	// close rules button
	$body.on('click', 'button.close-rules', function () {
		$(this).hide();
		$(this).prev('button.see-rules').show();
		$(this).closest('tr').next('tr').hide();
	});

	// for RulesFactory // TODO: bring handlers below, to rulesFactory.
	// operator and bool condition attribute change,
	// and input type change on OP_BOOL
	$(document).on('change', '.event-rule-operator, .condition-bool', function () {
		var $th = $(this);
		// toggle selected option.
		$th.find('option[selected]').removeAttr('selected');
		$th.find('option[value=' + $th.val() + ']').attr('selected', 'selected');

		if ($th.hasClass('event-rule-operator')) {
			var $template = $('#event-rules-template');

			// switching bool and input[type=text]
			if ($th.find('option:selected').val() == 'OP_BOOL') {
				var $conditionBool = $template.find('select.condition-bool').clone();
				$conditionBool.find('option:first').attr('selected', 'selected');
				$th.parents('div.event-rule').find('input.event-rule-value').replaceWith($conditionBool);
			} else {
				var $conditionValue = $template.find('input.event-rule-value').clone();
				$th.parents('div.event-rule').find('select.condition-bool').replaceWith($conditionValue);
			}
		}
	});

	// event -> rules -> conditions:
	// delete condition
	$(document).on('click', '#event-rules-translate a.delete-event-rule', function (e) {
		e.preventDefault();
		$(this).closest('div.event-rule').remove();
		return false;
	});

	// event -> rules -> conditions:
	// add condition for rule
	$(document).on('click', '#event-rule-add-condition', function (e) {
		e.preventDefault();

		var $template = $('#event-rules-template');
		var $ruleCondition = $template.find('div.event-rule').clone();
		// making first option selected
		$ruleCondition.find('select.event-rule-operator > option').eq(1).attr('selected', 'selected');
		var $resultHtml = $('<div>', {'class': 'row'}).append($ruleCondition);
		$('#event-rules-translate').append($resultHtml);

		return false;
	});

});

// Rules factory class
// needs template
// container :: rules container :: input\selects
// input\selects model:
// - 1st [key]-[operator^}-[value],
// - 2nd [rule_operator]-[key]-[operator^}-[value]
RulesFactory = function () {

	var $template = $('#event-rules-template');

	this.operators = [
		{'bool': 'OP_BOOL'},
		{'>': 'OP_GREATER'},
		{'>=': 'OP_GREATER_OR_EQUAL'},
		{'<': 'OP_LESS'},
		{'<=': 'OP_LESS_OR_EQUAL'},
		{'=': 'OP_EQUAL'},
		{'!=': 'OP_NOT_EQUAL'}
	];

	// forming event rules[] to editable inputs
	// placing inside form
	this.formFromJson = function ($sRule) {

		var $oRule = $.parseJSON($sRule);

		$.each($oRule, function (index, rule) {
			var $conditionsHtml = $template.find('div.event-rule').clone();

			$conditionsHtml.find('input[name="event-rule-name"]').val(rule.name);
			if (rule.operator == 'OP_BOOL') {
				// putting switch trigger
				var $selectBool = $template.find('select.condition-bool').clone();
				$selectBool.find('option[value=' + rule.value + ']').attr('selected', 'selected');
				$conditionsHtml.find('input.event-rule-value').replaceWith($selectBool);
			} else {
				// common text input
				$conditionsHtml.find('input[name="event-rule-value"]').val(rule.value);
			}
			$conditionsHtml.find('option[value=' + rule.operator + ']').attr('selected', 'selected');

			var $resultHtml = $('<div>', {'class': 'row'}).append($conditionsHtml);
			$resultHtml.appendTo('#event-rules-translate');
		});
	};

	this.clear = function () {
		$(document).off('change', '.event-rule-operator');
	};

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
function updateRuleRow(oData) {
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

/**
 * When forming rule JSON from inputs
 * returning right type
 *
 * @param string
 * @returns {*}
 */
function typeFromString(string) {

	if (string === 'true') {
		return true;
	} else if (string === 'false') {
		return false;
	} else if (isNaN(parseFloat(string))) {
		return string;
	} else {
		return parseFloat(string);
	}
}

/**
 * Update rules counter inside see-rules button
 *
 * @param $th
 * @param oData
 */
function updateRulesCounter($th, oData) {
	var $eventId = $th.closest('tr.event-rules-row').data('event-rules');
	var $ruleRow = $('#events-rules').find('tr[data-id=' + $eventId + ']');
	var $seeRules = $ruleRow.find('button.see-rules');

	$seeRules.data('rules-count', oData.data.count);
	$seeRules.find('span').text(oData.data.count);
}
</script>