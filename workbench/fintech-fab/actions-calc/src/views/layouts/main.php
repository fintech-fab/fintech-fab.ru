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

<!DOCTYPE html><!--suppress HtmlUnknownTarget -->
<html class="no-js" lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FintechFab::actions-calc(Калькулятор событий)</title>

	<link rel="stylesheet" href="<?php echo $sPubPath; ?>/css/app.min.css">
	<link rel="stylesheet" href="<?php echo $sPubPath; ?>/css/custom.css">

	<script src="<?php echo $sPubPath; ?>/js/app.min.js"></script>
</head>
<body>

<div class="row">
	<div class="large-12 columns">
		<div class="nav-bar right">
			<ul class="button-group">
				<?php if (\FintechFab\ActionsCalc\Components\AuthHandler::isClientRegistered()): ?>
					<li>
						<button id="auth-profile" class="button secondary small"><i class="fi-torso"></i></button>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<h1>
			<small><?php echo link_to_route('calc.manage', 'Калькулятор событий'); ?></small>
		</h1>
		<hr />
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		<?php echo $content; ?>
	</div>
</div>


<footer class="row">
	<div class="large-12 columns">
		<hr />
	</div>
</footer>

<!-- f5 framework -->
<script src="<?php echo $sPubPath; ?>/js/cf.min.js"></script>
<!-- /f5 framework -->
<script>
	$(document).foundation({
		reveal: {
			animation_speed: 50
		}
	});
</script>

</body>
</html>