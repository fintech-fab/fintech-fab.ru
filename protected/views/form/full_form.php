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

<div class="row">

	<?php $this->widget('CheckBrowserWidget'); ?>

	<?php
	Yii::app()->clientScript->registerScript('fullFormOnload', '
				$(function($){
					var active = $("#accordion1").find(".in");
					var href= active.attr("data-href");
					if(!active.find(".accordion-inner").html().trim())
						active.find(".accordion-inner").load(href);
			}
         );
		', CClientScript::POS_END);

	Yii::app()->clientScript->registerCoreScript('yiiactiveform');
	Yii::app()->clientScript->registerCoreScript('maskedinput');
	?>



	<div class="span12">
		<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array(
			'id'          => 'accordion1',
			'toggle'      => false,
			'htmlOptions' => array(
				'class'  => 'accordion',
			),
			'events'      => array(
				'show' => 'js: function(){
					var clicked = $(this).find(":focus").parents(".accordion-group").find(".accordion-body");
					var href= clicked.attr("data-href");
					if(!clicked.find(".accordion-inner").html().trim())
						clicked.find(".accordion-inner").load(href);
                    }'
			),
			'options'     => array(
				''

			),

		));?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
					Collapsible Group Item #1 </a>
			</div>
			<div id="collapseOne" class="accordion-body collapse in" data-href="/form/ajaxForm">
				<div class="accordion-inner">

				</div>
			</div>
		</div>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo" data-href="/form/ajaxForm">
					Collapsible Group Item #2 </a>
			</div>
			<div id="collapseTwo" class="accordion-body collapse" data-href="/form/ajaxForm">
				<div class="accordion-inner">

				</div>
			</div>
		</div>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree" data-href="/form/ajaxForm">
					Collapsible Group Item #3 </a>
			</div>
			<div id="collapseThree" class="accordion-body collapse" data-href="/form/ajaxForm">
				<div class="accordion-inner">

				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>


