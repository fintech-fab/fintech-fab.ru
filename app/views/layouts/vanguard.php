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
<?= View::make('layouts.inc.navbar.navbar') ?>
<?= View::make('vanguard.flash_message') ?>

<div class="container content"><?= $content ?></div>
</body>
</html>
