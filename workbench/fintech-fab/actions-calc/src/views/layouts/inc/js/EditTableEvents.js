$(document).ready(function () {
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


	$('button#saveEditTableEvent').click(function () {
		var $btn = $(this);
		var id = $btn.data('id');
		var sid = $btn.data('sid');

		var name = $('#inputName').val();
		var event_sid = $('#inputSid').val();
		if (event_sid == sid) {
			$.post('tableEvents/changeData/', {
					id: id,
					unique: false,
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
		} else {
			$.post('tableEvents/changeData/', {
					id: id,
					unique: true,
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

		}


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

	$('button.tableGetRules').click(function () {

		var $btn = $(this);
		var id = $btn.data('id');

		$.post('tableEvents/getRules/', {
				event_id: id
			},
			function (data) {
				if (data['errors']) {
					alert(data['errors']['event_id']);
					return;
				}

				$(".rulesForEvents").hide("slow", function () {
					$('.rulesForEvents').remove();
				});
				$('.colored').css({'background-color': ''});
				$btn.parent().parent().addClass('colored').css({'background-color': '#FFFACD'});
				$btn.parent().parent().after(
					"<tr class='rulesForEvents' style='opacity: 0;'><td colspan='4' style='background-color: #FFFACD; '><div class='test' style='margin-left: 35px;  '>" + data + "</div></td></tr>"
				);

				$(".rulesForEvents").animate({'opacity': '1'}, 1000);


			}
		);

	});

});


