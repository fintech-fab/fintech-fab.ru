<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
/* @var $aAmountValues */
/* @var $aTimeValues */
/* @var $aPercentage */
?>

	<h5 class="pay_legend">Укажите сумму</h5>
<?= $form->dropDownList($model, 'amount', $aAmountValues, array('class' => 'hide', 'id' => 'amount')); ?>
	<br />    <br />    <br />    <h5 class="pay_legend">Укажите срок займа</h5>
<?= $form->dropDownList($model, 'time', $aTimeValues, array('class' => 'hide', 'id' => 'time')); ?>
<?php //TODO вынести CSS в файл ?>
	<style type="text/css">
		.ui-slider {
			position: relative;
			text-align: left;
		}

		.ui-slider .ui-slider-handle {
			position: absolute;
			z-index: 2;
			width: 1.2em;
			height: 2.2em;
			cursor: default;
			background-color: #0064cd;
			background-repeat: repeat-x;
			background-image: -khtml-gradient(linear, left top, left bottom, from(#049cdb), to(#0064cd));
			background-image: -moz-linear-gradient(top, #049cdb, #0064cd);
			background-image: -ms-linear-gradient(top, #049cdb, #0064cd);
			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #049cdb), color-stop(100%, #0064cd));
			background-image: -webkit-linear-gradient(top, #049cdb, #0064cd);
			background-image: -o-linear-gradient(top, #049cdb, #0064cd);
			background-image: linear-gradient(top, #049cdb, #0064cd);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#049cdb', endColorstr='#0064cd', GradientType=0);
			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
			border-color: #0064cd #0064cd #003f81;
			border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
		}

		.ui-slider .ui-slider-handle:hover,
		.ui-slider .ui-slider-handle:focus {
			background-position: 0 -15px;
			text-decoration: none;
			transition: background-position 0.1s linear 0s;
			background-color: #0064cd;
			color: #333;
		}

		.ui-slider .ui-slider-range {
			position: absolute;
			z-index: 1;
			font-size: .7em;
			display: block;
			border: 0;
		}

		.ui-slider-horizontal {
			height: 1.6em;
		}

		.ui-slider-horizontal .ui-slider-handle {
			top: -.3em;
			margin-left: -.6em;
		}

		.ui-slider-horizontal .ui-slider-range {
			top: 0;
			height: 100%;
		}

		.ui-slider-horizontal .ui-slider-range-min {
			left: 0;
		}

		.ui-slider-horizontal .ui-slider-range-max {
			right: 0;
		}

		.ui-slider-vertical {
			width: 1.8em;
			height: 100px;
		}

		.ui-slider-vertical .ui-slider-handle {
			left: -.3em;
			margin-left: 0;
			margin-bottom: -.6em;
		}

		.ui-slider-vertical .ui-slider-range {
			left: 0;
			width: 100%;
		}

		.ui-slider-vertical .ui-slider-range-min {
			bottom: 0;
		}

		.ui-slider-vertical .ui-slider-range-max {
			top: 0;
		}

		/* Tabs*/

		.ui-slider span.ui-slider-tic {
			height: 1.6em;
			left: 0;
			position: absolute;
			top: -1.3em;
		}

		.ui-slider li span.ui-widget-content, .ui-slider dd span.ui-widget-content {
			border-bottom: 0 none;
			border-right: 0 none;
			border-style: none none none solid;
			border-top: 0 none;
			border-width: 0 0 0 0;
		}

		.ui-slider li span.ui-slider-label-show, .ui-slider dd span.ui-slider-label-show {
			margin-top: 1em;
		}

		.ui-slider li span.ui-slider-label, .ui-slider dd span.ui-slider-label {
			margin-top: 1em;
		}
	</style>


<?php
Yii::app()->clientScript->registerScript('sliderWidgetVars', '
	var aPercentage = ' . CJSON::encode($aPercentage) . ';
	', CClientScript::POS_BEGIN);
?>
<?php
Yii::app()->clientScript->registerScript('sliderWidget', '
			var oAmount = $("#amount");
			var oTime = $("#time");
			oAmount.selectToUISlider({
				labels: 7,
				tooltip: false
			});


			oAmount.change(function () {
				$(".cost.final_price").html(this.value);
				$(".cost.price_count").html(parseInt(this.value)+aPercentage[]);

			});
			oTime.change(function () {
				$(".cost.date").html(getDateToPayUntil(this.value));
			});


			oTime.selectToUISlider({
				labels: 7,
				tooltip: false
			});

			oAmount.change();
			oTime.change();


	', CClientScript::POS_LOAD);
?>