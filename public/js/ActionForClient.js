$(document).ready(function () {

	$('#buy').click(function () {
		// если блок уже открыт, то закрыть его
		$("#buy_window").slideToggle('slow');
	});

	$('#buy-operation').click(function () {
		var sum = $('#inputSum').val();
		var tel = $('#inputTel').val();
		var comment = $('#inputComment').val();
		var userID = $('#user_id').val();
		$.post('shop-page', { sum: sum, tel: tel, comment: comment, userID: userID },
			function (data) {
				alert(data);
				if (data['errors']) {
					$('#errorSum').html(data['errors'][0]);
					$('#errorTel').html(data['errors'][1]);
				} else {
					$('#errorSum').html('');
					$('#errorTel').html('');
				}

			}
		);

	});
});
