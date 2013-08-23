<?php
/*
 * @var $this Controller
 */
?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div class="row">
		<div class="span12">
			<?php
			if (!Yii::app()->user->isGuest) {
				$this->widget('admin.components.MenuWidget');
			}
			?>
		</div>
	</div>
	<!-- menu -->
	<div class="clearfix"></div>
	<div class="row">
		<div class="span12">
			<?php echo $content; ?>
		</div>
	</div>
	<!-- content -->
</div>
<?php $this->endContent(); ?>
