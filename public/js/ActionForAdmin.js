$(document).ready(function () {

	$('#btnRoles').click(function () {  //Загрузка таблицы ролей
		var tableRoles = $('#tableRoles');
		if (tableRoles.css('display') == 'none') {
			getRoleUsersTable(1);
			tableRoles.show();
			$('#btnRoles').text("Скрыть таблицу ролей");

		}
		else {
			tableRoles.hide();
			$('#btnRoles').text("Загрузить таблицу ролей");
		}

	});



	/**
	 *
	 * @param integer $pageNum
	 */
	function getRoleUsersTable($pageNum)
	{
		$.ajax({
			url: "TableForAdmin",
			data: {"pageNum":$pageNum},
			success: function (data) {
				if (typeof data == "string")
					$("#message").append(data).css({color: "red", textAlign: "center"});
				else {
					var $roles = data['userRoles'];
					var x = [];
					x[0] = "<tr><td><b>№</b></td>" +
						"<td><b>Имя</b></td>" +
						"<td><b>Фамилия</b></td>" +
						"<td><b>Админ</b></td>" +
						"<td><b>Модератор</b></td>" +
						"<td><b>Пользователь</b></td>" +
						"<td><b>Рассыльный</b></td>" +
						"<td><b>Подписчик</b></td></tr>";
					for (var i = 0; i < $roles.length; i++)
					{
						x[i+1] = "<tr><td>" + $roles[i].id + "</td>" +
							"<td>" + $roles[i].first_name + "</td>" +
							"<td>" + $roles[i].last_name + "</td>" +
							"<td><input type='checkbox' id='Checkbox1' value='1' " + $roles[i].admin + "></td>" +
							"<td><input type='checkbox' id='Checkbox2' value='2' " + $roles[i].moderator + "></td>" +
							"<td><input type='checkbox' id='Checkbox3' value='3' " + $roles[i].user + "></td>" +
							"<td><input type='checkbox' id='Checkbox4' value='4' " + $roles[i].messageSender + "></td>" +
							"<td><input type='checkbox' id='Checkbox5' value='5' " + $roles[i].messageSubscriber + "></td></tr>";
					}

					$("#tableUser").empty().append(x);
					pageNavigation(data['pageNum'], data['pageMax']);
				}

				$("input[type=checkbox]").click(function (event) {
					var tr = $(event.target).closest("tr");
					//var userN = tr.prevAll().length;
					var userN = tr.children("td:first").html();
					var roleN = this.value;
					var val = this.checked;
					$.get('changeRole', {"userN": userN, "roleN": roleN, "val": val},
						function (res) {
							$("#message").dialog({ title: 'Сообщение', show: 'drop', hide: 'explode' }).html(res);
						});
				});
			}
		});

	}



	/**
	 * Buttons for navigation of pages
	 *
	 */
	$('#UserPages *').filter("button").click(function (event) {
		var btn = $(event.target);
		var qweryPage = 1;
		if(btn.is("#btnPLeft"))		{
			qweryPage = parseInt($("#btnP1").html()) - 1;
		} else if(btn.is("#btnPRight"))		{
			qweryPage = parseInt($("#btnP5").html()) + 1;
		} else		{
			qweryPage = btn.html();
		}
		getRoleUsersTable(qweryPage);
	});


	/**
	 * Navigation of pages
	 *
	 * @param integer curPage
	 * @param integer maxPage
	 */
	function pageNavigation(curPage, maxPage)
	{
		if(maxPage < 2){
			$("#UserPages").css('display','none');
			return;
		}
		$("#UserPages").css('display','inline');

		$("#UserPages > li > .active").removeClass("disabled active");

		if(maxPage <= 5){
			$('#btnsPLeft').css('display','none');
			$('#btnsPRight').css('display','none');
			for (var i = maxPage + 1; i <= 5; i++){
				$("#btnP" + i).css('display','none');
			}
			$("#btnP"+curPage).addClass("disabled active");

		} else
		{
			$('#btnPEnd').html(maxPage);
			var btn1Val = 1;

			if(curPage <= 5){
				$('#btnsPLeft').css('display','none');
				if(maxPage > 5) {
					$('#btnsPRight').css('display','inline');
				}
			} else {
				$('#btnsPLeft').css('display','inline');

				if(curPage <= maxPage - 5){
					$('#btnsPRight').css('display','inline');
					btn1Val = curPage - (curPage % 5) + 1;
					if((curPage % 5)==0){
						btn1Val -= 5;
					}
				} else {
					$('#btnsPRight').css('display','none');
					btn1Val = maxPage -  5 + 1;
				}
			}
			if(btn1Val != $("#btnP1").html()){
				$("#btnP1").html(btn1Val);
				$("#btnP2").html(btn1Val + 1);
				$("#btnP3").html(btn1Val + 2);
				$("#btnP4").html(btn1Val + 3);
				$("#btnP5").html(btn1Val + 4);
			}

			$("#btnP"+(curPage - (btn1Val - 1))).addClass("disabled active");
		}
	}// End. Navigation of pages.


});
