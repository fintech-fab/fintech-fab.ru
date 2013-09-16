<?php
/* @var FormController $this
 */

// превышено число попыток ввода SMS.

$this->pageTitle = Yii::app()->name;
?>

<div class="row">
	<div class="alert alert-block alert-error">
		<?= Dictionaries::C_ERR_SMS_TRIES ?>
	</div>
	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>
</div>
