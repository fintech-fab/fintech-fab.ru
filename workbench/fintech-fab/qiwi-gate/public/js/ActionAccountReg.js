$(document).ready(function () {
	$('button#accountRegSubmit').click(function () {
		var user_id = $('#inputId').val();
		var username = $('#inputUsername').val();
		var callback = $('#inputCallback').val();
		var password = $('#inputPassword').val();
		var confirmPassword = $('#inputConfirmPassword').val();
		$.ajax({
			type: "POST",
			url: '',
			data: {user_id: user_id,
				username: username,
				callback: callback,
				password: password,
				confirmPassword: confirmPassword
			},
			success: function (data) {
				if (data['errors']) {
					$('#errorId').html(data['errors']['id']);
					$('#errorUsername').html(data['errors']['username']);
					$('#errorCallback').html(data['errors']['callback']);
					$('#errorPassword').html(data['errors']['password']);
					$('#errorConfirmPassword').html(data['errors']['confirmPassword']);
					return;
				}
				$('#message').dialog({
					title: 'Сообщение', show: 'fade', hide: 'fade', modal: true, close: function () {
						location.reload();
					}
				}).html(data['message']);
			}
		});
	});
});
