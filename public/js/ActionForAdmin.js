$(document).ready(function () {

	$('#users').click(function () {

		$("#tableUser").load('workAdmin', function infoForAdmin(info) {
			var decode = JSON.parse(info);
			var x = new Array();
			x[0] = "<tr><td><b>№</b></td><td><b>Имя</b></td>" +
				"<td><b>Фамилия</b></td><td><b>Админ</b></td>" +
				"<td><b>Модератор</b></td><td><b>Пользователь</b></td></tr>";
			for (var i = 1; i <= decode.length; i++) {
				//alert(decode[i]);
				x[i] = "<tr><td>" + i + "</td><td>" + decode[i - 1].first_name +
					"</td><td>" + decode[i - 1].last_name + "</td>" +
					"<td><input type='checkbox' id='Checkbox1' value='admin' " + decode[i - 1].admin + "></td>" +
					"<td><input type='checkbox' id='Checkbox2' value='moderator' " + decode[i - 1].moderator + "></td>" +
					"<td><input type='checkbox' id='Checkbox3' value='user' " + decode[i - 1].user + "></td></tr>";
			}
			$("#tableUser").empty().append(x);
		}, "json");
	});

});
