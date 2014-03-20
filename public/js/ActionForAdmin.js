$(document).ready(function () {

	$('#users').click(function () {
		$.ajax({
			url: "workAdmin",
			success: function (decode) {
				var x = [];
				x[0] = "<tr><td><b>№</b></td><td><b>Имя</b></td>" +
					"<td><b>Фамилия</b></td><td><b>Админ</b></td>" +
					"<td><b>Модератор</b></td><td><b>Пользователь</b></td></tr>";
				for (var i = 1; i <= decode.length; i++) {
					//alert(decode[i]);
					x[i] = "<tr><td>" + i + "</td><td>" + decode[i - 1].first_name +
						"</td><td>" + decode[i - 1].last_name + "</td>" +
						"<td><input type='checkbox' id='Checkbox1' value='1' " + decode[i - 1].admin + "></td>" +
						"<td><input type='checkbox' id='Checkbox2' value='2' " + decode[i - 1].moderator + "></td>" +
						"<td><input type='checkbox' id='Checkbox3' value='3' " + decode[i - 1].user + "></td></tr>";
				}
				$("#tableUser").empty().append(x);
				$("input[type=checkbox]").click(function (event) {
					var tr = $(event.target).closest("tr");
					var userN = tr.prevAll().length;
					var roleN = this.value;
					var val = this.checked;
					$.get('changeRole', {"userN": userN, "roleN": roleN, "val": val},
						function () {
							alert("Изменения произошли");
						});
				});
			}
		});


	});

});
