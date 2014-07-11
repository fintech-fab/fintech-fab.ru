<?php
if (empty($content)) {
	$content = '';
}
?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('ff-actions-calc::layouts.inc.head') ?>
</head>
<body>
<?= View::make('ff-actions-calc::layouts.inc.navbar') ?>
<div class="content"><?= $content ?></div>
</body>
</html>
