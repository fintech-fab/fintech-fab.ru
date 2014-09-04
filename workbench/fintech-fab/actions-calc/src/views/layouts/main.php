<?php
/**
 * File main.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var string $content
 */
?>

<?php $sPubPath = asset('packages/fintech-fab/actions-calc/'); ?>

<!DOCTYPE html>
<html class="no-js" lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FintechFab::ActionsCalculator</title>

	<link rel="stylesheet" href="<?php echo $sPubPath; ?>/css/app.min.css">
	<link rel="stylesheet" href="<?php echo $sPubPath; ?>/css/custom.css">
	<link rel="stylesheet" href="<?php echo $sPubPath; ?>/datatables/plugins/foundation/dataTables.foundation.css">

	<script src="<?php echo $sPubPath; ?>/js/app.js"></script>
	<script src="<?php echo $sPubPath; ?>/datatables/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $sPubPath; ?>/datatables/plugins/foundation/dataTables.foundation.js"></script>
</head>
<body>

<div class="row">
	<div class="large-12 columns">
		<div class="nav-bar right">
			<ul class="button-group">
				<?php if (\FintechFab\ActionsCalc\Components\AuthHandler::isClientRegistered()): ?>
					<li><a href="#" class="button"><i class="fi-torso"></i></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<h1>
			<small>Калькулятор событий</small>
		</h1>
		<hr />
	</div>
</div>

<div class="row">
	<div class="large-12 columns" role="content">
		<?php echo $content; ?>
	</div>
</div>


<footer class="row">
	<div class="large-12 columns">
		<hr />
	</div>
</footer>

<script src="<?php echo $sPubPath; ?>/js/cf.js"></script>
<script>
	$(document).foundation({
		reveal: {
			animation_speed: 50
		}
	});
</script>

</body>
</html>