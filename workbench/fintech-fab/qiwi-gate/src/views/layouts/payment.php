<?php
if (empty($content)) {
	$content = '';
}
?>
<!DOCTYPE html>
<html>
<head>
	<?= View::make('ff-qiwi-gate::layouts.inc.head') ?>
	<script src="/packages/fintech-fab/qiwi-gate/js/ActionPayment.js"></script>
</head>
<body>
<div><?= $content ?></div>
</body>
</html>
