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
			<div class="large-9 columns search-field-wrap">
				<input id="search-event-text" name="search-event" type="text" placeholder="Искать события">
			</div>
			<div class="large-3 columns">
				<button id="search-event" class="button postfix"><i class="fi-magnifying-glass"></i></button>
			</div>
		</div>
	</div>
	<div class="large-3 columns">
		<button id="search-revert" class="button success tiny" style="display: none;"><i class="fi-loop"></i>&nbsp;к
			событиям
		</button>
	</div>
	<div class="large-6 columns">
		<ul class="button-group right">
			<li>
				<a id="manage-chain" href="#" class="button secondary small right">
					Цепочкой&nbsp;<i class="fi-plus"></i>&nbsp;<i class="fi-plus"></i></a>
			</li>
			<li>
				<button data-reveal-id="modal-event-create" id="event-create" class="button small right"> Добавить
					событие <i class="fi-plus"></i></button>
			</li>
		</ul>
	</div>
</div><!-- /buttons: search and add event -->

<!-- modal event create -->
<div id="modal-event-create" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::event.create'); ?>
</div><!-- /modal create event--><!-- modal event update-->
<div id="modal-update-event" class="reveal-modal small" data-reveal></div><!-- /modal update event--><!-- modal search event -->

<!-- modal rule create -->
<div id="modal-rule-create" class="reveal-modal medium" data-reveal></div><!-- /modal rule update -->
<!-- modal rule update -->
<div id="modal-rule-update" class="reveal-modal medium" data-reveal></div><!-- /modal rule update -->

<!-- modal signal create -->
<div id="modal-signal-create" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::signal.create'); ?>
</div><!-- /modal signal create --><!-- modal signal create -->
<div id="modal-signal-udpate" class="reveal-modal small" data-reveal></div><!-- /modal signal create -->


<!-- events table -->
<div id="events-table-container">
	<?php /** @noinspection PhpUndefinedMethodInspection */
	$events->setBaseUrl('/actions-calc/events/table'); ?>
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<!-- temp storage -->
<div id="events-table-container-search" class="hide search-mode"></div><!-- /temp storage -->

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

	// datatable
	var $signalsTable = $('#manage-signals').DataTable({
		language: {
			"sProcessing": "Подождите...",
			"sLengthMenu": "Показать _MENU_ записей",
			"sZeroRecords": "Записи отсутствуют.",
			"sInfo": "Записи с _START_ до _END_ из _TOTAL_ записей",
			"sInfoEmpty": "Записи с 0 до 0 из 0 записей",
			"sInfoFiltered": "(отфильтровано из _MAX_ записей)",
			"sInfoPostFix": "",
			"sSearch": "Поиск: ",
			"sUrl": "",
			"oPaginate": {
				"sFirst": "Первая",
				"sPrevious": "Предыдущая",
				"sNext": "Следующая",
				"sLast": "Последняя"
			},
			"oAria": {
				"sSortAscending": ": активировать для сортировки столбца по возрастанию",
				"sSortDescending": ": активировать для сортировки столбцов по убыванию"
			}
		}
	});
	var oButtons = {
		edit: '<ul class="signal-buttons button-group right">' +
		'<li><button class="tiny button signal-edit">&nbsp;<i class="fi-page-edit"></i></button>' +
		'<button class="tiny button alert signal-delete">&nbsp;<i class="fi-x"></i></button></li></ul>'
	};
	var oSignalRow = {
		DT_RowId: null,
		0: '',
		1: '',
		2: oButtons.edit
	};

	// events:
	// events table pagination
	// pagination through ajax
	$body.on('click', 'ul.pagination a', function (e) {
		e.preventDefault();

		var $eventTableContainer = $('#events-table-container');
		$eventTableContainer.prepend($('<div class="table-loading"></div>'));

		$.get($(this).attr('href'),
			function (oData) {
				$eventTableContainer.empty();
				$eventTableContainer.append(oData);
			},
			'html'
		).always(function () {
				$eventTableContainer.find('div.table-loading').remove();
			});

		return false;
	});

	// events:
	// modal event create
	$body.on('click', '#button-event-create', function (e) {
		e.preventDefault();

		// $th button event create
		var $button = $(this);
		var $form = $button.closest('form');

		buttonSleep($button);

		$.post('/actions-calc/event/create',
			$form.serialize(),
			function (oData) {
				if (oData.status == 'success') {
					$('#modal-event-create').foundation('reveal', 'close');
					updateEventsTable();
					clearFormErrors($form);
				} else if (oData.status == 'error') {
					revealFormErrors($form, oData.errors);
				}
				return false;
			},
			'json'
		).always(function () {
				buttonWakeUp($button);
			});

		return false;
	});
	// events:
	// event udpate modal - open
	$body.on('click', 'button.edit-rule', function () {

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
	$body.on('click', '#button-event-update', function (e) {
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
	// modal event delete // TODO: count event on every deletion, if less than 10, update events table.
	$body.on('click', '#events-rules button.delete-rule', function () {
		// $th clicked delete button
		var $button = $(this);
		buttonSleep($button);

		var $thisRow = $button.closest('tr');
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
				} else if (oData.status == 'error') {
					alert(oData.message);
				}
				return false;
			},
			'json'
		).always(function () {
				buttonWakeUp($button);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// events:
	// events search
	$body.on('click', 'button#search-event', function () {

		var $button = $(this);
		var sSearchQuery = $('input#search-event-text').val();

		buttonSleep($button);
		$.get(
			'/actions-calc/event/search?q=' + sSearchQuery,
			function (oData) {

				var $eventTableContainer = $('#events-table-container');

				// finding search container, pulling data, changing id
				if ($eventTableContainer.hasClass('search-mode') == false) {

					var $containerSearch = $('#events-table-container-search');

					// find results to separate container
					$containerSearch.html(oData);
					$containerSearch.attr('id', 'events-table-container');
					$containerSearch.removeClass('hide');

					// hiding earlier showed events
					$('#events-table-container:not(.search-mode)').attr('id', 'events-table-container-hidden').addClass('hide');

					// showing revert to get back to normal events showing
					$('#search-revert').fadeIn(50);
				} else {
					$eventTableContainer.html(oData);
				}
			},
			'html'
		).always(function () {
				buttonWakeUp($button);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// events:
	// events search revert button
	// hides search table, reveals opened earlier
	$body.on('click', 'button#search-revert', function () {

		var $button = $(this);

		buttonSleep($button);
		// switching event tables: search results -><- opened results
		var $eventTableContainer = $('#events-table-container.search-mode');
		var $eventHidden = $('#events-table-container-hidden');

		$eventTableContainer.addClass('hide');
		$eventTableContainer.attr('id', 'events-table-container-search');

		$eventHidden.removeClass('hide');
		$eventHidden.attr('id', 'events-table-container');

		// updating hidden events table
		updateEventsTable();
		buttonWakeUp($button);

		// hiding search revert button
		$button.hide(); // TODO: add event table update, on certain page.

		return false;
	});
	// events:
	// search field keydown activate search on [enter]
	$body.on('keydown', 'input#search-event-text', function (e) {

		var $input = $(this);
		var sQuery = $input.val();

		// hit [enter]
		if (e.keyCode == 13) {
			console.log(sQuery);
			console.log(sQuery.length);
			if (sQuery.length < 2 || sQuery === undefined) {
				//			updateEventsTable();
				return false;
			}

			$('button#search-event').trigger('click');

			return false;
		}
	});

	// event -> rules:
	// toggle event rules flag
	$body.on('click', '#event-rules-wrap div.switch label', function () {
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

		return false;
	});

	// events -> rules:
	// [Rule Create] - open
	$body.on('click', 'button.rule-create', function () {

		var $th = $(this);
		var $modalRuleCreate = $('#modal-rule-create');
		var $toEventId = $th.closest('tr').data('id');

		buttonSleep($th);
		$.get(
			'/actions-calc/rule/create',
			$th.closest('form').serialize(),
			function (oData) {
				$modalRuleCreate.html(oData);
				// event id to hidden input
				$modalRuleCreate.find('input[name="event_id"]').val($toEventId);
				$modalRuleCreate.find('select.s2').select2();
				$modalRuleCreate.foundation('reveal', 'open');
			},
			'html'
		).always(function () {
				buttonWakeUp($th);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// events -> rules:
	// [Rule Create] - create
	// forming JSON string from rule conditions
	$body.on('click', '#button-rule-create', function () {

		var $submit = $(this);
		var $modalRuleCreate = $('#modal-rule-create');
		// finding container with rule conditions
		var $rulesContainer = $modalRuleCreate.find('.event-rules-translate');
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
		$submit.closest('form').find('input[name="rule"]').val(sRules);

		// update rule request
		buttonSleep($submit);

		$.post(
			'/actions-calc/rule/create',
			$submit.closest('form').serialize(),
			function (oData) {
				if (oData.status == 'success') {
					updRulesCountFromEvent(oData);
					// finding rule_id passed to #modal-rule-create
					var $ruleId = $('#modal-rule-create').find('input[name="event_id"]').val();
					updateEventRules($ruleId, $('#events-table-container'));
					$modalRuleCreate.foundation('reveal', 'close');
				} else if (oData.status == 'error') {
					revealFormErrors($submit.closest('form'), oData.errors);
				}
			},
			'json'
		).always(function () {
				buttonWakeUp($submit);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// events -> rules:
	// [Rule Update] - open
	$body.on('click', 'button.rule-update', function () {

		var $th = $(this);
		var $ruleId = $th.closest('tr').data('id');
		var $modalRuleUpdate = $('#modal-rule-update');

		buttonSleep($th);
		$.get(
			'/actions-calc/rule/update/' + $ruleId,
			$th.closest('form').serialize(),
			function (oData) {
				$modalRuleUpdate.html(oData);
				$modalRuleUpdate.find('select.s2').select2();
				$modalRuleUpdate.foundation('reveal', 'open');

				var $sRule = $modalRuleUpdate.find('input[name="rule"]').val();
				var rulesFactory = new RulesFactory();
				rulesFactory.formFromJson($sRule, $modalRuleUpdate.find('.event-rules-translate'));
			},
			'html'
		).always(function () {
				buttonWakeUp($th);
			});

		return false;
	});
	// events -> rules:
	// [Rule Update] - update
	// forming JSON string from rule conditions
	$body.on('click', '#button-rule-update', function (e) {
		e.preventDefault();

		// finding container with rule conditions
		var $th = $(this);
		var $rulesContainer = $th.closest('form').find('div.event-rules-translate');
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
					updateEventRules($ruleId, $('#events-table-container'));
					$('#modal-rule-update').foundation('reveal', 'close');
				} else if (oData.status == 'error') {
					revealFormErrors($th.closest('form'), oData.errors);
				}
			},
			'json'
		).always(function () {
				buttonWakeUp($th);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// events -> rules:
	// rule delete button - delete
	$body.on('click', 'button.rule-delete', function () {

		// $th clicked delete button
		var $delButton = $(this);
		buttonSleep($delButton);

		var $thisRow = $delButton.closest('tr');
		var $ruleId = $thisRow.data('id');

		$.post('/actions-calc/rule/delete/' + $ruleId,
			function (oData) {
				if (oData.status == 'success') { // success
					// update event -> rules button counter
					updRulesCountFromRules($delButton, oData);
					console.log($('button.see-rules').data('rules-count'));
					// deleted, removing table records and opened rules, if exists
					$thisRow.fadeOut();
					// closing rules table if 0 rules
					if (oData.data.count <= 0) {
						var $parentEventRow = $delButton.parents('tr.event-rules-row');
						var iParentEventId = $parentEventRow.data('event-rules');
						$parentEventRow.prev('tr[data-id=' + iParentEventId + ']').find('button.close-rules').click();
					}
				}
				buttonWakeUp($delButton);
				return false;
			},
			'json'
		).always(function () {
				buttonWakeUp($delButton);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});
		;

		return false;
	});
	// events -> rules:
	// close rules button
	$body.on('click', 'button.close-rules', function () {
		$(this).hide();
		$(this).prev('button.see-rules').show();
		$(this).closest('tr').next('tr').hide();
		return false;
	});

	// signals
	// signal store
	$body.on('click', '#button-signal-create', function () {

		var $button = $(this);
		var $form = $button.closest('form');

		buttonSleep($button);

		$.post(
			'/signal',
			$button.closest('form').serialize(),
			function (oData) {
				if (oData.status == 'success') {

					clearFormErrors($form);
					$('#modal-signal-create').foundation('reveal', 'close');

					// new row to datatable
					oSignalRow.DT_RowId = oData.data.id;
					oSignalRow[0] = oData.data.signal_sid;
					oSignalRow[1] = oData.data.name;

					$signalsTable.row.add(oSignalRow).draw().node();
				} else if (oData.status == 'error') {
					revealFormErrors($form, oData.errors);
				}
			},
			'json'
		).always(function () {
				buttonWakeUp($button);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// signal update - open form
	$body.on('click', '.signal-edit', function () {

		var $button = $(this);
		var iSignalId = +$button.closest('tr').attr('id');

		buttonSleep($button);

		$.get(
			'/signal/' + iSignalId + '/edit',
			function (oData) {
				var $modalUpdate = $('#modal-signal-udpate');
				$modalUpdate.html(oData);
				$modalUpdate.foundation('reveal', 'open');
			},
			'html'
		).always(function () {
				buttonWakeUp($button);
			}).fail(function (xhr) {
				alert(xhr.responseText);
			});

		return false;
	});
	// signal delete
	$body.on('click', '.signal-delete', function () {

		var $button = $(this);
		var iSignalId = +$button.closest('tr').attr('id');

		buttonSleep($button);

		$.ajax({
			method: 'DELETE',
			url: '/signal/' + iSignalId,
			success: function (oData) {
				if (oData.status == 'success') {
					$button.closest('tr[id=' + iSignalId + ']').fadeOut();
				}
			},
			dataType: 'json'
		}).always(function () {
			buttonWakeUp($button);
		}).fail(function (xhr) {
			alert(xhr.responseText);
		});

		return false;
	});
	// signal update - put signal
	$body.on('click', '#signal-update-button', function (e) {
		e.preventDefault();

		var $button = $(this);
		var $form = $button.closest('form');
		var iSignalId = $form.data('id');

		buttonSleep($button);

		$.ajax({
			method: 'PUT',
			url: '/signal/' + iSignalId,
			data: $form.serialize(),
			success: function (oData) {
				if (oData.status == 'success') {
					$('#modal-signal-udpate').foundation('reveal', 'close');
					clearFormErrors($form);

					// updating signals table
					oSignalRow[0] = oData.data.signal_sid;
					oSignalRow[1] = oData.data.name;
					$signalsTable.row('[id=' + iSignalId + ']').data(oSignalRow).draw();

				} else if (oData.status == 'error') {
					revealFormErrors($form, oData.errors);
				}
			},
			dataType: 'json'
		}).always(function () {
			buttonWakeUp($button);
		}).fail(function (xhr) {
			alert(xhr.responseText);
		});

		return false;
	});

	// for RulesFactory // TODO: bring handlers below, to rulesFactory.
	// operator and bool condition attribute change,
	// and input type change on OP_BOOL
	$body.on('change', '.event-rule-operator, .condition-bool', function () {
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
	$body.on('click', '.event-rules-translate a.delete-event-rule', function (e) {
		e.preventDefault();
		$(this).closest('div.event-rule').remove();
		return false;
	});
	// event -> rules -> conditions:
	// [Condition Add] for rule
	$body.on('click', 'button.event-rule-add-condition', function () {

		var $template = $('#event-rules-template');
		var $ruleCondition = $template.find('div.event-rule').clone();
		// making first option selected
		$ruleCondition.find('select.event-rule-operator > option').eq(1).attr('selected', 'selected');
		var $resultHtml = $('<div>', {'class': 'row'}).append($ruleCondition);
		// finding rules-translate container
		// and pushing new condition
		$(this).parent().prev('fieldset').find('.event-rules-translate').append($resultHtml);

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
	this.formFromJson = function ($sRule, selector) {

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
			$resultHtml.appendTo(selector.closest('.event-rules-translate'));
//			$resultHtml.appendTo('#event-rules-translate');
		});
	};

	this.clear = function () {
		$('body').off('change', '.event-rule-operator');
	};

	this.settings = {
	};

};

/**
 * Update events table
 *
 * @returns {boolean}
 */
function updateEventsTable() {
	var $eventTableContainer = $('#events-table-container');

	var iPage = $('#pagination-events-current-page').text();

	$.get('/actions-calc/events/table?page=' + iPage,
		function (oData) {
			$eventTableContainer.html(oData);
		},
		'html'
	);

	return false;
}

/**
 * Put event rules in container
 *
 * @param ruleId
 * @param $eventsContainer
 */
function updateEventRules(ruleId, $eventsContainer) {
	$.post(
		'/actions-calc/manage/get-event-rules',
		{event_id: ruleId},
		function (oData) {
			var $eventRulesTable = $eventsContainer.find('tr[data-event-rules=' + ruleId + ']');

			if ($eventRulesTable !== undefined) {
				$eventRulesTable.find('#event-rules-wrap').replaceWith(oData);
			}
		},
		'html'
	).fail(function (xhr) {
			alert(xhr.responseText);
		});

	return false;
}

/**
 * Update event name and event_sid on update
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
	$(document).off('click', button);
}

/**
 * Blocking button
 *
 * @param button
 */
function buttonSleep(button) {
	button.attr('disabled', 'disabled');
	$(document).on('click', button, function () {
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
 * Update rules counter inside see-rules button, from rules table
 *
 * @param $button
 * @param oData
 */
function updRulesCountFromRules($button, oData) {
	// finding parent row with button, comparing id's
	var $rulesTableRow = $button.closest('tr.event-rules-row');
	var $eventId = $rulesTableRow.data('event-rules');
	var $buttonSeeRules = $rulesTableRow.prev('tr[data-id=' + $eventId + ']').find('button.see-rules');

	$buttonSeeRules.data('rules-count', oData.data.count);
	$buttonSeeRules.find('span').text(oData.data.count);
}

/**
 * Update rules counter inside see-rules button, from event
 *
 * @param oData
 */
function updRulesCountFromEvent(oData) {
	var iEventId = $('#modal-rule-create').find('input[name="event_id"]').val();
	var $ruleRow = $('#events-table-container:not(.in-modal)').find('tbody tr[data-id=' + iEventId + ']');
	var $seeRules = $ruleRow.find('button.see-rules');

	$seeRules.data('rules-count', oData.data.count);
	$seeRules.find('span').text(oData.data.count);
}

</script>