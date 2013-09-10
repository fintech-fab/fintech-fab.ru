<?php /* @var $this Controller */

?>

<?php $this->beginContent('/layouts/main'); ?>
<div class="container" id="main-content">
	<div class="row">
		<div class="span8">
			<h3 class="pay_legend">Личный кабинет</h3><br />
			<?php echo $content; ?>
		</div>
		<!-- content -->
		<div class="span4">
			<div class="well" style="padding: 8px; 0; margin-top: 20px;">
				<?php

				$this->beginWidget('bootstrap.widgets.TbMenu', array(
					'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
					'stacked'     => true, // whether this is a stacked menu
					'items'       => $this->menu,
					'htmlOptions' => array('style' => 'margin-bottom: 0;'),
				));
				?>

				<div style="padding-left: 20px;">
					<?php
					(Yii::app()->adminKreddyApi->isSmsAuth())
						? $this->renderPartial('is_sms_auth')
						: $this->renderPartial('not_sms_auth');
					?>
				</div>
				<?php $this->endWidget(); ?>

			</div>
			<!-- sidebar -->
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
