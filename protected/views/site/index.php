<?php
/* @var $this SiteController */
/* @var $model ClientJoinForm */
/* @var $form CActiveForm */


$this->pageTitle = Yii::app()->name;

$this->showTopPageWidget = true;
?>
<style type="text/css">

	.main_row label {
		margin-top: 5pt;
		margin-right: 5pt;
		float: left;
	}

	.required span {
		float: none;
	}


</style>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php $this->widget('StepsBreadCrumbsWidget', array(
				'curStep' => 1,
			)); ?>
		</div>
	</div>
	<div class="row">
		<div class="span10 offset1">
			<div class="form">
				<?php $model = new ClientJoinForm;
				?>
				<?php $form = $this->beginWidget('CActiveForm', array(
					'id'                   => 'client-join',
					'action'               => array('site/join'),
					'enableAjaxValidation' => true,
				)); ?>
				<!--?= $form->errorSummary($model); ?-->
				<div class="row main_row">
					<?= $form->labelEx($model, 'phone'); ?>+7
					<?= $form->textField($model, 'phone'); ?>
					<?= $form->error($model, 'phone'); ?>
				</div>
				<div class="row buttons">
					<?=
					CHtml::submitButton('Далее →',
						array('class' => 'btn btn-info')
					); ?>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>
