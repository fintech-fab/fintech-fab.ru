<?php
if (empty($content)) {
	$content = '';
}

?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('ff-qiwi-shop::layouts.inc.head') ?>
</head>
<body>
<?= View::make('ff-qiwi-shop::layouts.inc.navbar') ?>
<div class="content"><?= $content ?></div>
</body>
</html>
