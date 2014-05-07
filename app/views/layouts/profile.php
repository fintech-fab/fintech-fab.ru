<?php
if (empty($content)) {
	$content = '';
}

?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('layouts.inc.head.head') ?>
	<link rel="stylesheet" href="css/styleForProfile.css" />
</head>
<body>
<?= View::make('layouts.inc.navbar.navbar') ?>
<?= View::make('layouts.inc.head.flash_message') ?>
<div class="container"><?= $content ?></div>
</body>
</html>
