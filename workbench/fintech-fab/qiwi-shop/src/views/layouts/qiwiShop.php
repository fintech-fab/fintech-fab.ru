<?php
if (empty($content)) {
	$content = '';
}
/**
 * @var string $userMessage
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('ff-qiwi-shop::layouts.inc.head') ?>
</head>
<body>
<?= View::make('ff-qiwi-shop::layouts.inc.flash_message') ?>
<?= View::make('ff-qiwi-shop::layouts.inc.navbar') ?>
<div class="container"><?= $content ?></div>
</body>
</html>
