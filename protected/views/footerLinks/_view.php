<?php
/* @var $this FooterLinksController */
/* @var $data FooterLinks */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('link_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->link_id), array('view', 'id'=>$data->link_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('link_name')); ?>:</b>
	<?php echo CHtml::encode($data->link_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('link_title')); ?>:</b>
	<?php echo CHtml::encode($data->link_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('link_url')); ?>:</b>
	<?php echo CHtml::encode($data->link_url); ?>
	<br />

</div>