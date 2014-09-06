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
	<link rel="stylesheet" href="css/dinner.css"/>
	<script src="js/dinner.js"></script>

</head>
<body>
<?= View::make('layouts.inc.navbar.navbar') ?>
<?= View::make('layouts.inc.head.flash_message') ?>
<div class="container"><?= $content ?></div>
</body>
</html>
