$(document).ready(function () {
	$('button#payBill').click(function () {
		var user = $('#inputTel').val();
		$.ajax({
			type: "POST",
			url: '',
			data: {user: user},
			success: function (data) {
				if (data['error']) {
					$('#error').html(data['error']);
					return;
				} else {
					$('#error').html('');
				}
				$('#message').dialog({
					title: 'Сообщение', show: 'fade', hide: 'fade', modal: true, close: function () {
						window.close();
					}
				}).html(data['message']);
			}
		});
	});
});
