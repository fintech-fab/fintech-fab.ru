$(document).ready(function () {

	$("input[type=checkbox]").click(function (event) {
		var tr = $(event.target).closest("tr");
		var userN = tr.children("td:first").html();
		var roleN = this.value;
		var val = this.checked;
		$.get('admin/changeRole', {"userN": userN, "roleN": roleN, "val": val},
			function (res) {
				$("#message").dialog({ title: 'Сообщение', show: 'drop', hide: 'explode' }).html(res);
			});
	});

});
