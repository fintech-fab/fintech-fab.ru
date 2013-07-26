<?php
/* @var $this TabsController */
/* @var $data Tabs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('tab_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->tab_id), array('view', 'id'=>$data->tab_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tab_name')); ?>:</b>
	<?php echo CHtml::encode($data->tab_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tab_title')); ?>:</b>
	<?php echo CHtml::encode($data->tab_title); ?>
	<br />


</div>
