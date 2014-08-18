<?php
/**
 * File main.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
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
	<script src="<?php echo $sPubPath; ?>/js/app.js"></script>

	<!-- select2 -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/select2/3.4.8/select2.css">
	<script src="//cdn.jsdelivr.net/select2/3.4.8/select2.min.js"></script>
</head>
<body>

<div class="row">
	<div class="large-12 columns">
		<div class="nav-bar right">
			<ul class="button-group">
				<li><a href="#" class="button"><i class="fi-torso"></i></a></li>
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

		<ul class="tabs" data-tab>
			<li class="tab-title active"><a href="#panel0-1"><i class="fi-arrow-down"></i>&nbsp;Events</a></li>
			<li class="tab-title"><a href="#panel0-2"><i class="fi-list-thumbnails"></i>&nbsp;Rules</a></li>
			<li class="tab-title"><a href="#panel0-3"><i class="fi-arrow-right"></i>&nbsp;Signals</a></li>
		</ul>

		<div class="tabs-content">
			<div class="content active" id="panel0-1">
				<p>First panel content goes here...</p>
			</div>
			<div class="content" id="panel0-2">
				<p>Second panel content goes here...</p>
				<script>
					$(document).ready(function () {
						$("#e1").select2();
					});
				</script>

				<select id="e1">
					<option value="AL">Alabama</option>
					<option value="WY">Wyoming</option>
					<option value="WY">Wyoming</option>
					<option value="WY">Wyoming</option>
					<option value="WY">Wyoming</option>
				</select>
			</div>
			<div class="content" id="panel0-3">
				<p>Third panel content goes here...</p>
			</div>
		</div>

	</div>


</div>


<footer class="row">
	<div class="large-12 columns">
		<hr />
		<div class="row">
			<div class="large-6 columns">
				<p>© Copyright no one at all. Go to town.</p>
			</div>
			<div class="large-6 columns">
				<ul class="inline-list right">
					<li><a href="#">Link 1</a></li>
					<li><a href="#">Link 2</a></li>
					<li><a href="#">Link 3</a></li>
					<li><a href="#">Link 4</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>

<script src="<?php echo $sPubPath; ?>/js/cf.js"></script>
<script>$(document).foundation();</script>

</body>
</html>