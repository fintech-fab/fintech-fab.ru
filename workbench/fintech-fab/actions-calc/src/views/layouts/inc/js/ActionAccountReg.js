$(document).ready(function () {
	$('button#accountRegSubmit').click(function () {
		var termId = $('#inputId').val();
		var username = $('#inputUsername').val();
		var url = $('#inputUrl').val();
		var queue = $('#inputQueue').val();
		var key = $('#inputKey').val();
		var password = $('#inputPassword').val();
		var confirmPassword = $('#inputConfirmPassword').val();
		$.post('account/newTerminal', {
				termId: termId,
				username: username,
				url: url,
				queue: queue,
				key: key,
				password: password,
				confirmPassword: confirmPassword
			},
			function (data) {
				if (data['errors']) {
					$('#errorId').html(data['errors']['id']);
					$('#errorUsername').html(data['errors']['username']);
					$('#errorUrl').html(data['errors']['url']);
					$('#errorQueue').html(data['errors']['queue']);
					$('#errorKey').html(data['errors']['key']);
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
		);
	});
});
