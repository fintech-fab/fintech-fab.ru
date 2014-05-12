$(document).ready(function () {
	$('button.tableBtn').click(function () {

		var $btn = $(this);
		var action = $btn.data('action');
		var id = $btn.data('id');

		$.ajax({
			type: "POST",
			url: 'orders/' + action,
			data: {order_id: id},
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
		var $btn = $(this);
		var $modal = $('#payReturn');
		$modal.data('id', $btn.data('id'));
		$modal.data('action', $btn.data('action'));
	});

	$('button.payReturnModal').click(function () {

		var $modal = $(this).parents('#payReturn');
		var action = $modal.data('action');
		var id = $modal.data('id');

		var sum = $modal.find('#inputSum').val();
		var comment = $modal.find('#inputComment').val();
		var url = 'orders/' + action;
		var data = {order_id: id, sum: sum, comment: comment};

		$.ajax({
			type: "POST",
			url: url,
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
