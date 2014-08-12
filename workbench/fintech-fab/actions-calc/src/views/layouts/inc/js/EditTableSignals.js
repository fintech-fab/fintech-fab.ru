$(document).ready(function () {
	$('button.tableEditBtn').click(function () {

		var $btn = $(this);
		var signal = $btn.data('signal');

		$('button.changeDataModal').attr({
			'data-id': signal.id,
			'data-sid': signal.signal_sid
		});
		$('#inputName').val(signal.name);
		$('#inputSid').val(signal.signal_sid);

	});

	$('button#saveChangeSignal').click(function () {

		var $btn = $(this);
		var id = $btn.data('id');
		var sid = $btn.data('sid');

		var name = $('#inputName').val();
		var signal_sid = $('#inputSid').val();

		$.post('tableSignals/changeData/', {
				id: id,
				name: name,
				signal_sid: signal_sid
			},
			function (data) {
				if (data['errors']) {
					$('#errorName').html(data['errors']['name']);
					$('#errorSid').html(data['errors']['signal_sid']);
					return;
				}
				location.reload();
			}
		);

	});

	$('button#AddSignalTable').click(function () {
		var name = $('#inputNameAdd').val();
		var signal_sid = $('#inputSidAdd').val();
		$.post('tableSignals/addData/', {
				name: name,
				signal_sid: signal_sid
			},
			function (data) {
				if (data['errors']) {
					$('#errorNameAdd').html(data['errors']['name']);
					$('#errorSidAdd').html(data['errors']['signal_sid']);
					return;
				}
				location.reload();
			}
		);

	});

});


