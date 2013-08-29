<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="row">
		<div class="span9">
			<?php echo $content; ?>
		</div>
		<!-- content -->
		<div class="span3">
			<div id="sidebar">
				<div class="well" style="padding: 8px; 0;">
					<?php
					//$this->menu[] = array('label' => 'LIST HEADER');
					$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('site/logout')));

					$this->beginWidget('bootstrap.widgets.TbMenu', array(
						'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
						//'stacked'     => false, // whether this is a stacked menu
						'items'       => $this->menu,
						'htmlOptions' => array('style' => 'margin-bottom: 0;'),
					));
					$this->endWidget();
					?>
				</div>
			</div>
			<!-- sidebar -->
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
