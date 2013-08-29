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
				<?php
				$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

				/*$this->beginWidget('zii.widgets.CPortlet', array(
					'title' => 'Меню',
				));*/
				$this->widget('bootstrap.widgets.TbMenu', array(
					'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
					'stacked'     => false, // whether this is a stacked menu
					'items'       => $this->menu,
					'htmlOptions' => array('class' => 'operations'),
				));
				//$this->endWidget();
				?>
			</div>
			<!-- sidebar -->
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
