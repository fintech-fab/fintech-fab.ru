<?php
/* @var $this PagesController */
/* @var $data Pages */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('page_id')); ?>:</b>
	<?php echo CHtml::encode($data->page_id); ?>
	<br/>

	<b><?php echo CHtml::encode($data->getAttributeLabel('page_name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->page_name), array('view', 'name'=>$data->page_name)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('page_title')); ?>:</b>
	<?php echo CHtml::encode($data->page_title); ?>
	<br />

	<!--b><!--?php echo CHtml::encode($data->getAttributeLabel('page_content')); ?>:</b>
	<!--?php //echo CHtml::encode($data->page_content); ?>
	<br /-->

</div>