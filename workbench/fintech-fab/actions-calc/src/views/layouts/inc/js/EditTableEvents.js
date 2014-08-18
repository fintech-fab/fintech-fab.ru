$(document).ready(function () {
	$('select').css({padding: 0});

	$('button.tableEditBtn').click(function () {
		$('#errorSid').empty();
		var $btn = $(this);
		var event = $btn.data('event');

		$('button.changeDataModal').attr({
			'data-id': event.id,
			'data-sid': event.event_sid
		});
		$('#inputName').val(event.name);
		$('#inputSid').val(event.event_sid);
	});


	$('button.addRule').click(function () {
		$('.text-danger').empty();
		var $btn = $(this);
		var id = $btn.data('id');
		$('#inputEventSidAdd').val(id);
		$('select').select2();
		$('#inputEventSidAdd').select2("readonly", true);

	});


	$('button#saveEditTableEvent').click(function () {
		var $btn = $(this);
		var id = $btn.data('id');
		var sid = $btn.data('sid');
		var name = $('#inputName').val();
		var event_sid = $('#inputSid').val();

		$.post('tableEvents/changeData/', {
				id: id,
				name: name,
				event_sid: event_sid
			},
			function (data) {
				if (data['errors']) {
					$('#errorName').html(data['errors']['name']);
					$('#errorSid').html(data['errors']['event_sid']);
					return;
				}
				location.reload();
			}
		);
	});

	$('button#AddEventTable').click(function () {
		var name = $('#inputNameAdd').val();
		var event_sid = $('#inputSidAdd').val();

		$.post('tableEvents/addData/', {
				name: name,
				event_sid: event_sid
			},
			function (data) {
				if (data['errors']) {
					$('#errorNameAdd').html(data['errors']['name']);
					$('#errorSidAdd').html(data['errors']['event_sid']);
					return;
				}
				location.reload();
			}
		);

	});
	$('button.tableGetRules').click({x: 0}, function (e) {
		if (e.data.x != this) {
			var btn = $(this);
			btn.button('loading');
			if (e.data.x) {
				$('.active .refreshRules').attr({
					'disabled': 'disabled'
				});
				$(e.data.x)
					.closest('tr')
					.removeClass('active')
					.next()
					.find('.rulesForEvents')
					.slideUp(500, function () {
						$(this).closest('tr').remove();
					});
			}
			var $btn = $(this);
			var $act = $btn.closest('tr').addClass('active');
			$('.active .refreshRules').removeAttr('disabled');

			$.post(
				'tableEvents/getRules/',
				{ event_id: $btn.data('id') },
				function (data) {
					if (data['errors']) {
						btn.button('reset');
						$act.after('<tr class="active inside"><td colspan="4" style="border-top: 1px solid black;"><div class="rulesForEvents"><b>' + data['errors']['event_id'] + '</b></div></td></tr>')
							.next()
							.find('.rulesForEvents')
							.slideDown(500);
						return;
					}
					btn.button('reset');
					$act.after('<tr class="active"><td colspan="4" style="border-top: 1px solid black;"><div class="rulesForEvents">' + data + '</div></td></tr>')
						.next()
						.find('.rulesForEvents')
						.slideDown(500);
				});

			e.data.x = this;
		} else {
			$('.active .refreshRules').attr({
				'disabled': 'disabled'
			});
			$(e.data.x)
				.closest('tr')
				.removeClass('active')
				.next()
				.find('.rulesForEvents')
				.slideUp(500, function () {
					$(this).closest('tr').remove();
				});
			e.data.x = false;
		}
	});
	var i = 1;
	$('#addSignalInput').click(function () {

		$('.addSignal').before('<div class="form-group row">​' +
				'<label for="inputSignalSidAdd' + i + '" class="col-sm-3 control-label">signal_sid</label>' +
				'<div class="col-sm-9"><div class="SignalSid SignalSid' + i + '">' +
				'</div></div>' +
				'<div id="errorSignalSidAdd' + i + '" class="text-danger text-center"></div></div>').after(function () {
			$('#inputSignalSidAdd').clone().appendTo('.SignalSid' + i).val('').attr({"id": 'inputSignalSidAdd' + i}).addClass('inputSignalSidAdd').select2();
			i++;
		});

	});


	$('#removeLastSignal').click(function () {
		var signalSid = $('.SignalSid');
//		alert(signalSid);
		if (signalSid.length > 1) {
			$('.SignalSid:last').parent().parent().remove();
		}
	});


	$('button.addDataRuleTable').click(function () {
		$('.text-danger').empty();
		var eventSid = $('#inputEventSidAdd').val();
		var signalSid = {};
		$('select.inputSignalSidAdd').each(function () {
			var id = $(this).attr("id");
			signalSid[id] = $(this).val();
		});
		var name = $('#inputNameAddRule').val();
		var rule = $('#inputRuleAdd').val();
		var btn = $(this);
		btn.button('loading');
		$.post('tableRules/addData/', {
				event_id: eventSid,
				signal_id: signalSid,
				rule: rule,
				name: name
			},
			function (data) {
				btn.button('reset');
				if (data['errors']) {
					if (data['errors']['signal_id']) {
						for (var id in data['errors']['signal_id']) {
							var errorId = 'error' + id.substring(5);
							$('#' + errorId).html(data['errors']['signal_id'][id]);
						}
					}

					$('#errorNameAddRule').html(data['errors']['name']);
					$('#errorEventSidAdd').html(data['errors']['event_id']);
					$('#errorRuleAdd').html(data['errors']['rule']);
					return;
				}
				var div = $('#addDataRuleModal');
				div.modal('hide');
				Example.show("Правило добавлено!");
//				location.reload();
			}
		);
	});

	$('.refreshRules').click(function () {
		var btn = $(this);
		var id = btn.data('id');
		$.post(
			('tableEvents/getRules/'),
			{ event_id: id },
			function (data) {
				if (data['errors']) {
					return;
				}
				$('.rulesForEvents').empty().html(data);

			});
	});
});
