$(document).ready(function () {

	$('#EventSid').find('select').css({padding: 0});
	$('#SignalSid').find('select').css({padding: 0});
	$('.EventSid').find('select').css({padding: 0});
	$('.SignalSid').find('select').css({padding: 0});

	$("input[type=checkbox]").click(function () {
		var btn = this;
		var id = this.id;
		var val = btn.checked;
		bootbox.confirm("Изменить правило #" + id + '?', function (result) {
			if (result) {
				$.post('tableRule/changeFlagRule/', {"id": id, "val": val},
					function () {
						if (val) {
							Example.show("Правило #" + id + " активно!");
						} else {
							Example.show("Правило #" + id + " неактивно!");
						}

					});
			} else {
				btn.checked = !val;
			}
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
		$('#inputEventSid').val(rule.event_id);
		$('#inputSignalSid').val(rule.signal_id);
		$('#inputNameRule').val(rule.name);
		$('#inputRule').val(rule.rule);
	});

	$('button.tableAddBtn').click(function () {
		$('#inputEventSidAdd').val('');
		$('#inputSignalSidAdd').val('');
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
		var eventSidId = $('#inputEventSid').val();
		var signalSidId = $('#inputSignalSid').val();
		var name = $('#inputNameRule').val();
		var rule = $('#inputRule').val();
		var btn = $(this);
		btn.button('loading');
		$.post('tableRules/changeData/', {
				event_id: eventSidId,
				signal_id: signalSidId,
				rule: rule,
				name: name,
				id: id
			},
			function (data) {
				btn.button('reset');
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
		$('.text-danger').empty();
		var eventSid = $('#inputEventSidAdd').val();
		var signalSid = {};
		$('select.inputSignalSidAdd').each(function () {
			var id = $(this).attr("id");
			signalSid[id] = $(this).val();
		});
		var name = $('#inputNameAdd').val();
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
					for (var id in data['errors']['signal_id']) {
						var errorId = 'error' + id.substring(5);
						$('#' + errorId).html(data['errors']['signal_id'][id]);
					}
					$('#errorNameAdd').html(data['errors']['name']);
					$('#errorEventSidAdd').html(data['errors']['event_id']);
					$('#errorRuleAdd').html(data['errors']['rule']);

					return;
				}
				location.reload();
			}
		);
	});


	$('select').select2();


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
		if (signalSid.length > 1) {
			$('.SignalSid:last').parent().parent().remove();
		}
	});

});


