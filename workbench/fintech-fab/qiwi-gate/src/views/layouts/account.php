<?php
if (empty($content)) {
	$content = '';
}
?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('ff-qiwi-gate::layouts.inc.head') ?>
</head>
<body>
<?= View::make('ff-qiwi-gate::layouts.inc.navbar') ?>
<div class="content"><?= $content ?></div>
</body>
</html>
