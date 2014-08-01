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
	$('button.tableGetRules').click({x: 0}, function (e) {
		if (e.data.x != this) {
			if (e.data.x) {
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

			$.post(
				'tableEvents/getRules/',
				{ event_id: $btn.data('id') },
				function (data) {
					if (data['errors']) {
						alert(data['errors']['event_id']);
						return;
					}
					$act.after('<tr class="active"><td colspan="4" style="background-color: #C9EEEC;border-top: 1px solid black;"><div class="rulesForEvents">' + data + '</div></td></tr>')
						.next()
						.find('.rulesForEvents')
						.slideDown(500);
				});

			e.data.x = this;
		}
	});
});


