$(document).ready(function () {

	$('#EventSid').find('select').css({padding: 0});
	$('#SignalSid').find('select').css({padding: 0});
	$('.EventSid').find('select').css({padding: 0});
	$('.SignalSid').find('select').css({padding: 0});

	$("input[type=checkbox]").click(function () {
		var id = this.id;
		var val = this.checked;
		$.post('tableRule/changeFlagRule/', {"id": id, "val": val},
			function (res) {
				$("#message").dialog({ title: 'Сообщение', show: 'drop', hide: 'explode' }).html(res);
			});
	});

	$('button.btnEdit').click(function () {
		var $btn = $(this);
		var rule = $btn.data('rule');
		$("#EventSid").find('.select2-chosen').html(rule.event.event_sid);
		$("#SignalSid").find('.select2-chosen').html(rule.signal.signal_sid);

		$('button#saveChangeRule').data('id', rule.id);
		$('#myModalLabel').html('Введите новые данные для правила #' + rule.id);

		$('#errorName').empty();
		$('#errorRule').empty();
		$('#errorEventSid').empty();
		$('#errorSignalSid').empty();

		$('#inputEventSid ').val(rule.event_id);
		$('#inputSignalSid').val(rule.signal_id);


		$('#inputName').val(rule.name);
		$('#inputRule').val(rule.rule);
	});

	$('button.tableAddBtn').click(function () {

		$(".EventSid .select2-chosen").html('');
		$(".SignalSid .select2-chosen").html('');

		$('#errorName').empty();
		$('#errorRule').empty();
		$('#errorEventSid').empty();
		$('#errorSignalSid').empty();

		$(".EventSid").find('input').attr('placeholder', 'Введите event_sid');
		$(".SignalSid").find('input').attr('placeholder', 'Введите signal_sid');


	});


	$('button#saveChangeRule').click(function () {
		var $btn = $(this);
		var id = $btn.data('id');

//		var eventSid = $('#EventSid').find('input').val();
//		var signalSid = $('#SignalSid').find('input').val();
		var eventSidId = $('#inputEventSid').val();
		var signalSidId = $('#inputSignalSid').val();
		var name = $('#inputName').val();
		var rule = $('#inputRule').val();
		$('button').attr('disabled', true);
		$.post('tableRules/changeData/', {
				event_id: eventSidId,
				signal_id: signalSidId,
				rule: rule,
				name: name,
				id: id
			},
			function (data) {
				$('button').attr('disabled', false);
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
		$('button').attr('disabled', true);
		$.post('tableRules/addData/', {
				event_id: eventSid,
				signal_id: signalSid,
				rule: rule,
				name: name
			},
			function (data) {
				$('button').attr('disabled', false);
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


	$('select').select2();


});


