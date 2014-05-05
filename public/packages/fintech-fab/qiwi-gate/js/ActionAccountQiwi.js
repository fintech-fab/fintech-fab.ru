$(document).ready(function () {

	$('button#changeAccountData').click(function () {
		var username = $('#inputUsername').val();
		var callback = $('#inputCallback').val();
		var password = $('#inputPassword').val();
		var confirmPassword = $('#inputConfirmPassword').val();
		var oldPassword = $('#inputOldPassword').val();
		$.ajax({
			type: "POST",
			url: 'account/changeData',
			data: {username: username,
				callback: callback,
				password: password,
				confirmPassword: confirmPassword,
				oldPassword: oldPassword
			},
			success: function (data) {
				if (data['errors']) {
					$('#errorUsername').html(data['errors']['username']);
					$('#errorCallback').html(data['errors']['callback']);
					$('#errorPassword').html(data['errors']['password']);
					$('#errorConfirmPassword').html(data['errors']['confirmPassword']);
					$('#errorOldPassword').html(data['errors']['oldPassword']);
					return;
				}
				location.reload();
			}
		});
	});
});
