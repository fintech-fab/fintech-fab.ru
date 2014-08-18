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
		$('.text-danger').empty();
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
					$('#errorNameRule').html(data['errors']['name']);
					$('#errorEventSid').html(data['errors']['event_id']);
					$('#errorRule').html(data['errors']['rule']);
					$('#errorSignalSid').html(data['errors']['signal_id']);
					return;
				}
				var div = $('#changeDataRuleModal');
				div.modal('hide');
				Example.show("Правило #" + id + " изменено!");
			}
		);
	});


	$('select').select2();


	$('.rulesForEvents').find('.pagination').find('a').click(function (event) {
		event.preventDefault();
		var href = this.href;
		var split = href.split('tableEvents/getRules');
		var btn = $('tr.active button.tableGetRules').data('id');

		$.post(
			('tableEvents/getRules/' + split[1]),
			{ event_id: btn },
			function (data) {
				if (data['errors']) {
					alert(data['errors']['event_id']);
					return;
				}
				$('.rulesForEvents').empty().html(data);

			});

	});

});


