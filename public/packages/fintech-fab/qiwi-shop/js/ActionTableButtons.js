$(document).ready(function () {
	$('.tableBtn').click(function () {
		var action = this.id.split('_');

		$.ajax({
			type: "POST",
			url: 'orders/' + action[0],
			data: 'order_id=' + action[1],
			success: function (data) {
				$('#message').dialog({
					title: data['title'], show: 'fade', hide: 'fade', modal: true, close: function () {
						location.reload();
					}
				}).html(data['message']);
			}
		});
	});
});

