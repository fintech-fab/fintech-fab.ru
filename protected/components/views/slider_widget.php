<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
/* @var $aSelectValues */
?>

<div class="row">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm',
		array(
			'id'                   => 'slider-form',
			'enableAjaxValidation' => true,
			'type'                 => 'horizontal',
			'clientOptions'        => array(
				'validateOnChange' => false,
				'validateOnSubmit' => true,
			),
			'action'               => Yii::app()->createUrl('/form/'),
		));

	?>
	<?= $form->dropDownList($model, 'amount', $aSelectValues, array('class'=>'hide','id'=>'amount')); ?>
	<br/>
	<?= $form->dropDownList($model, 'time', $aSelectValues, array('class'=>'hide','id'=>'time')); ?>

	<script type="text/javascript">
		/*jQuery(window).on('load',function() {
		 $("#slider .ui-slider-handle").tooltip({title: 'test', placement: 'bottom', trigger: 'manual', container: '#slider .ui-slider-handle'}).tooltip('show');
		 });*/

		$(function () {
			var oAmount = $('#amount');
			oAmount.selectToUISlider({
				labels: 7,
				tooltip: false
			});


			oAmount.change(function () {
				$(".cost.final_price").html(this.value);
				var n = 7;
				if (this.value >= 5000) n = 14;

				$(".cost.date").html(getDateToPayUntil(n));
			});

			var oTime = $('#time');
			oTime.selectToUISlider({
				labels: 7,
				tooltip: false
			});

		})
		;
	</script>

	<?php $this->endWidget(); ?>
</div>