<?php

?>
<script src="/js/ActionForAdmin.js" type="text/javascript"></script>
<div class="row">
	<div class="col-md-3">
		<img src="/assets/main/logo.png" height="100px" class="img" />
	</div>
	<div class="col-md-9">
		<h2 class="text-center">Здесь поле действия Администратора!</h2>
	</div>
</div>
<div class="text-center">

	<button id="users">Загрузить пользователей</button>


</div><br>
<div class="row">
	<div class="col-xs-10 col-xs-offset-1">
		<table class="table table-striped" id="tableUser">
			<tr>
				<td>№</td>
				<td>Имя</td>
				<td>Фамилия</td>
				<td>Админ</td>
				<td>Модератор</td>
				<td>Пользователь</td>
			</tr>
			<tr>
				<td>1</td>
				<td>Иван</td>
				<td>Иванов</td>
				<td>
					<input type="checkbox" id="Checkbox1" value="admin">
				</td>
				<td>
					<input type="checkbox" id="Checkbox2" value="moderator">
				</td>
				<td>
					<input type="checkbox" id="Checkbox3" value="user">
				</td>
			</tr>

		</table>
	</div>
</div>