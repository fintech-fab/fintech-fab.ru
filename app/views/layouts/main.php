<?php
if (empty($content)) {
	$content = '';
}
/**
 * @var string $userMessage
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?= View::make('layouts.inc.head.head') ?>
</head>
<body id="top">

<a href="#top" id="up" data-spy="affix" data-offset="100"><span class="fa fa-caret-up"></span></a>


<?= View::make('layouts.inc.head.flash_message') ?>

<?= $content ?>

<?= View::make('layouts.inc.footer.scripts') ?>

</body>
</html>