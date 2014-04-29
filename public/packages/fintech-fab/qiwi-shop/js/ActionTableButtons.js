$(document).ready(function () {
	$('.status').click(function () {
		var action = this.id.split('_');

		$.ajax({
			type: "GET",
			url: 'orders/' + action[1],
			success: function (data) {
				$('#message').dialog({ title: data['title'], show: 'fade', hide: 'fade' }).html(data['message']);
				if (data['change']) {
					$('.ui-dialog *').on('click', function () {
						location.reload();
					});
				}
			}
		});
	});

	$('.getBill').click(function () {
		var action = this.id.split('_');
		$.ajax({
			type: "PUT",
			url: 'orders/' + action[1],
			data: "name=John&location=Boston",
			success: function (data) {
				$('#message').dialog({ title: data['title'], show: 'fade', hide: 'fade' }).html(data['message']);
				if (data['change']) {
					$('.ui-dialog *').on('click', function () {
						location.reload();
					});
				}
			}
		});
	});
	$('.cancel').click(function () {
		var action = this.id.split('_');
		$.ajax({
			type: "DELETE",
			url: 'orders/' + action[1],
			data: "name=John&location=Boston",
			success: function (data) {
				alert(data);
				$('#message').dialog({ title: data['title'], show: 'fade', hide: 'fade' }).html(data['message']);
				if (data['change']) {
					$('.ui-dialog *').on('click', function () {
						location.reload();
					});
				}
			}
		});
	});
});

