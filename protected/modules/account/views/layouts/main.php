<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">

<head><!-- head start -->
	<?php $this->beginContent('//layouts/main_head');
	echo $content;
	$this->endContent();
	?>
	<!-- head end -->
</head>

<body class="home">
<!--  header navbar start -->
<?php $this->beginContent('//layouts/main_navbar_top');
echo $content;
$this->endContent();
?>
<!--  header navbar end -->

<!-- main content start-->
<?= $content; ?>
<!-- main content end-->

<br />

<!-- footer start-->
<?php $this->beginContent('//layouts/main_footer');
echo $content;
$this->endContent();
?>
<!-- footer start-->

</body>
</html>
