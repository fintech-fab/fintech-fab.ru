<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Ошибка';
$this->breadcrumbs=array(
	'Ошибка',
);
?>
<?php
$this->widget('TopPageWidget');
?>

<div class="container">
	<div class="row">
		<div class="span12">
			<h2>Ошибка <?php echo $code; ?></h2>
			<div class="error">
				<?php echo CHtml::encode($message); ?>
			</div>
		</div>
	</div>
</div>
