$(document).ready(function () {

	$('#users').click(function () {
		//$('#users').after("<div>Кнопка работает</div>")
		$("#tableUser").load('workAdmin', function infoForAdmin(info) {
			var decode = JSON.parse(info);
			var x = new Array();
			x[0] = "<tr><td><b>№</b></td><td><b>Имя</b></td><td><b>Фамилия</b></td></tr>";
			for (var i = 1; i <= decode.length; i++) {
				x[i] = "<tr><td>" + i + "</td><td>" + decode[i - 1].first_name + "</td><td>" + decode[i - 1].last_name + "</td></tr>";
			}
			$("#tableUser").empty().append(x);
		}, "json");
	});

});
