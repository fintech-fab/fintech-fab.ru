<?php
/**
 * @var array $userTable
 * @var mixed $pageLinks
 */
?>
<script src="/js/ActionForRoles.js" type="text/javascript"></script>
<div class="row">
	<div class="col-md-3">
		<img src="/assets/main/logo.png" height="100px" class="img" />
	</div>
	<div class="col-md-9">
		<h2 class="text-center">Страница администратора</h2>
	</div>
</div>

<div class="Roles">

	<div class="row mt20" id="tblRoles">
		<div class="col-xs-10 col-xs-offset-1">
			<table class="table table-striped" id="tableUser">
				<tr>
					<td><b>№</b></td>
					<td><b>Имя</b></td>
					<td><b>Фамилия</b></td>
					<td><b>Админ</b></td>
					<td><b>Модератор</b></td>
					<td><b>Пользователь</b></td>
					<td><b>Рассыльный</b></td>
					<td><b>Подписчик</b></td>
				</tr>
				<?php foreach($userTable as $user):?>

					<tr>
						<td><?=$user['id']; ?></td>
						<td><?=$user['first_name']; ?></td>
						<td><?=$user['last_name']; ?></td>
						<td>
							<input id="Checkbox1" type="checkbox" <?=$user['admin']; ?> value="1">
						</td>
						<td>
							<input id="Checkbox2" type="checkbox" <?=$user['moderator']; ?> value="2">
						</td>
						<td>
							<input id="Checkbox3" type="checkbox" <?=$user['user']; ?> value="3">
						</td>
						<td>
							<input id="Checkbox4" type="checkbox" <?=$user['messageSender']; ?> value="4">
						</td>
						<td>
							<input id="Checkbox5" type="checkbox" <?=$user['messageSubscriber']; ?> value="5">
						</td>
					</tr>
				<?php endforeach;?>

			</table>

			<?=$pageLinks; ?>

			<div id="message" class="row"></div>

		</div>
	</div>
</div>

<div class="clear clearfix mt20"></div>
