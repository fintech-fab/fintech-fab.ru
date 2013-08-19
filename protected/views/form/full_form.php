<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Цифровой код
 * Согласие с условиями и передачей данных
 */

$this->pageTitle = Yii::app()->name;

?>

<?php $this->widget('CheckBrowserWidget'); ?>

<?php
Yii::app()->clientScript->registerScript('fullFormOnload', '
				$(function($){
					/*var active = $("#accordion1").find(".in");
					var href= active.attr("data-href");
					if(!active.find(".accordion-inner").html().trim())
						active.find(".accordion-inner").load(href);*/
			}
         );
		', CClientScript::POS_END);
?>

<?php $this->widget('StepsBreadCrumbsWidget'); ?>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	//'enableAjaxValidation' => true,
	'type'=>'horizontal',
	'clientOptions'        => array(
	//	'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form/fullForm'),
));
?>

	<div class="row">
		<? require dirname(__FILE__) . '/fields2/personal_data.php' ?>
	</div>
	<div class="row">
		<? require dirname(__FILE__) . '/fields2/passport_data.php' ?>
	</div>
	<div class="row">
		<? require dirname(__FILE__) . '/fields2/address_reg.php' ?>
	</div>
	<div class="row">
		<? require dirname(__FILE__) . '/fields2/job_info.php' ?>
	</div>
	<div class="row">
		<? require dirname(__FILE__) . '/fields2/send.php' ?>
	</div>
	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Отправить →',
			)); ?>
		</div>
	</div>
	<?php $this->endWidget(); ?>
















	<br /><br /><br /><br /><br /><br />

	<?php /*$collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array(
		'id'          => 'accordion1',
		'toggle'      => false,
		'parent'      => false,
		'htmlOptions' => array(
			'class' => 'accordion',
		),
	));?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
				Collapsible Group Item #1 </a>
		</div>
		<div id="collapseOne" class="accordion-body collapse in">
			<div class="accordion-inner">

			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo" data-href="/form/ajaxForm">
				Collapsible Group Item #2 </a>
		</div>
		<div id="collapseTwo" class="accordion-body collapse">
			<div class="accordion-inner">

			</div>
		</div>
	</div>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree" data-href="/form/ajaxForm">
				Collapsible Group Item #3 </a>
		</div>
		<div id="collapseThree" class="accordion-body collapse">
			<div class="accordion-inner">

			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
