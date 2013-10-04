<?php /* @var $this Controller */

?>

<?php $this->beginContent('/layouts/main'); ?>
<div class="container" id="main-content">
	<div class="row">
		<div class="span8">
			<?php if(Yii::app()->user->hasFlash('success')):?>
			<div class="alert alert-success"><?= Yii::app()->user->getFlash('success'); ?></div>
			<?php endif; ?>
			<?= $content; ?>
		</div>
		<!-- content -->
		<div class="span4">
			<?php $this->renderPartial('menu'); ?>
		</div>

	</div>
</div>
<?php $this->endContent(); ?>
