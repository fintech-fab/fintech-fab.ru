$(document).ready(function () {

	$('#createOrder').click(function () {
		var item = $('#inputItem').val();
		var sum = $('#inputSum').val();
		var tel = $('#inputTel').val();
		var comment = $('#inputComment').val();
		$.post('.', { item: item, sum: sum, tel: tel, comment: comment},
			function (data) {
				if (data['errors']) {
					$('#errorItem').html(data['errors']['item']);
					$('#errorSum').html(data['errors']['sum']);
					$('#errorTel').html(data['errors']['tel']);
					$('#errorComment').html(data['errors']['comment']);
					$('#errorCommon').html(data['errors']['common']);
				} else {
					$('#errorItem').html('');
					$('#errorSum').html('');
					$('#errorTel').html('');
					$('#errorComment').html('');
				}
				if (data['result'] == 'ok') {
					$("#message").dialog({ title: 'Сообщение', show: 'fade', hide: 'fade' }).html(data['message']);
				}
			}
		);

	});
});
