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
	<?= View::make('layouts.inc.head.head') ?>
</head>
<body>
<?= View::make('vanguard.flash_message') ?>
<div class="container"><?= $content ?></div>
</body>
</html>
