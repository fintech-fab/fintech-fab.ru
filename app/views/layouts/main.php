<?php
if (empty($content)) {
	$content = '';
}
?>
<!DOCTYPE html>
<html>
<?= View::make('layouts.inc.head.head') ?>
<body>
<div class="container">
	<div><img src="/assets/main/logo.png" border="0" width="370" height="175" class="img" /></div>
	<div class="row text-center"><h2>[cайт на стадии разработки] <a href="/vanguard">[Стажировка]</a></h2><</div>
	<div class="row"><?= $content ?></div>


	<div class="row mt20">
		<div class="col-xs-2">
			<p style="font-size:18pt;">Наши<br>проекты:</p>
		</div>
		<div class="col-xs-2">
			<a href="http://kreddy.ru" target="_blank">
				<img src="/assets/main/kreddy.png" width="230" height="49" style="vertical-align:middle;" /> </a>
		</div>
		<div class="col-xs-3 pull-right text-right">
			<a class="fintech_lab_projects" href="http://fintech-lab.com/projects" target="_blank">
				<p class="projects">ПРОЕКТЫ</p>

				<p class="fintech_lab">FINTECH_LAB</p>
			</a>
		</div>
	</div>
	<div class='clear'>&nbsp;</div>

	<div class="row" style="height: 20px;">&nbsp;</div>
	<div class='clear'>&nbsp;</div>
</div>
</body>
</html>
