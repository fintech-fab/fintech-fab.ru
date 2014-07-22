$(document).ready(function () {
	$('button.tableEditBtn').click(function () {

		var $btn = $(this);
		var action = $btn.data('action');
		var id = $btn.data('id');

		$('button.changeDataModal').attr({
			'data-id': id
		});

		$('#inputName').val('');
		$('#inputSid').val('');

		$.post('tableEvents/getData/' + action, {id: id},
			function (data) {

				$('#inputName').val(data['name']);
				$('#inputSid').val(data['event_sid']);
			}
		);
	});

	$('button#actionBtn').click(function () {
		var $btn = $(this);
		var id = $btn.data('id');

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

});


