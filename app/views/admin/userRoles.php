<?php
/**
 * @var array $userTable
 * @var array $userTableHead
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

					<?php foreach($userTableHead as $roleHead): ?>
						<td><b><?=$roleHead ?></b></td>
					<?php endforeach; ?>
				</tr>
				<?php foreach($userTable as $user):?>

					<tr>
						<td><?=$user['id']; ?></td>
						<td><?=$user['first_name']; ?></td>
						<td><?=$user['last_name']; ?></td>
						<?php
						$i = 1;
						foreach($user['roles'] as $checkRole):
						?>
							<td>
								<?=	Form::checkbox("Checkbox" . $i, $i, $checkRole)	?>
							</td>
						<?php
							$i++;
						endforeach;
						?>

					</tr>
				<?php endforeach;?>

			</table>

			<?=$pageLinks; ?>

			<div id="message" class="row"></div>

		</div>
	</div>
</div>

<div class="clear clearfix mt20"></div>
