$(document).ready(function () {

	$("input[type=checkbox]").click(function () {
		var id = this.id;
		var val = this.checked;
		$.post('tableRule/changeFlagRule/', {"id": id, "val": val},
			function (res) {
				$("#message").dialog({ title: 'Сообщение', show: 'drop', hide: 'explode' }).html(res);
			});
	});

	$('button.tableEdit').click(function () {
		var $btn = $(this);
		var rule = $btn.data('rule');
		$('#inputEventSid').val(rule.event_id);
		$('#inputSignalSid').val(rule.signal_id);
		$('#inputName').val(rule.name);
		$('#inputRule').val(rule.rule);

		$('button#actionBtn').attr({
			'data-id': rule.id
		});
	});


	$('button#actionBtn').click(function () {
		var $btn = $(this);
		var id = $btn.data('id');

		var eventSid = $('#inputEventSid').val();
		var signalSid = $('#inputSignalSid').val();
		var name = $('#inputName').val();
		var rule = $('#inputRule').val();

		$.post('tableRules/changeData/', {
				event_id: eventSid,
				signal_id: signalSid,
				rule: rule,
				name: name,
				id: id
			},
			function (data) {
				if (data['errors']) {
					$('#errorName').html(data['errors']['name']);
					$('#errorEventSid').html(data['errors']['event_id']);
					$('#errorRule').html(data['errors']['rule']);
					$('#errorSignalSid').html(data['errors']['signal_id']);
					return;
				}
				location.reload();
			}
		);
	});

	$('button.addDataRuleTable').click(function () {
		var eventSid = $('#inputEventSidAdd').val();
		var signalSid = $('#inputSignalSidAdd').val();
		var name = $('#inputNameAdd').val();
		var rule = $('#inputRuleAdd').val();

		$.post('tableRules/addData/', {
				event_id: eventSid,
				signal_id: signalSid,
				rule: rule,
				name: name
			},
			function (data) {
				if (data['errors']) {
					$('#errorNameAdd').html(data['errors']['name']);
					$('#errorEventSidAdd').html(data['errors']['event_id']);
					$('#errorRuleAdd').html(data['errors']['rule']);
					$('#errorSignalSidAdd').html(data['errors']['signal_id']);
					return;
				}
				location.reload();
			}
		);
	});


});


