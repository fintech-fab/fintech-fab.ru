<?php /* @var $this Controller */

?>

<?php $this->beginContent('/layouts/main'); ?>
<div class="container" id="main-content">
	<div class="row">
		<div class="span8">
			<?= $content; ?>
		</div>
		<!-- content -->
		<div class="span4">
			<?php $this->renderPartial('menu_is_sms_auth'); ?>
		</div>

	</div>
</div>
<?php $this->endContent(); ?>
