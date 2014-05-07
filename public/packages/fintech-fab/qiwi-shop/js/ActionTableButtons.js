$(document).ready(function () {
	$('button.tableBtn').click(function () {
		var action = this.id.split('_');
		$.ajax({
			type: "POST",
			url: 'orders/' + action[0],
			data: {order_id: action[1]},
			success: function (data) {
				$('#message').dialog({
					title: data['title'], show: 'fade', hide: 'fade', modal: true, close: function () {
						location.reload();
					}
				}).html(data['message']);
			}
		});
	});
	$('button.actionBtn').click(function () {
		var action = this.id.split('_');
		$('.payReturnModal').attr('id', 'payReturn_' + action[1]);
	});
	$('button.payReturnModal').click(function () {
		var action = this.id.split('_');
		var sum = $('#inputSum').val();
		var comment = $('#inputComment').val();
		var data = {order_id: action[1], sum: sum, comment: comment};
		$.ajax({
			type: "POST",
			url: 'orders/' + action[0],
			data: data,
			success: function (data) {
				if (data['error']) {
					$('#errorSum').html(data['error']['sum']);
					$('#errorComment').html(data['error']['comment']);
					return;
				}
				$('#payReturn').modal('hide');
				$('#message').dialog({
					title: data['title'], show: 'fade', hide: 'fade', modal: true, close: function () {
						location.reload();
					}
				}).html(data['message']);
			}
		});
	});
});
