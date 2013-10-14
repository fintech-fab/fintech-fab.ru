<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
/* @var $aAmountValues */
/* @var $aTimeValues */
?>

	<h5 class="pay_legend">Укажите сумму</h5>
	<?= $form->dropDownList($model, 'amount', $aAmountValues, array('class'=>'hide','id'=>'amount')); ?>
	<br/>
	<br/>
	<br/>
	<h5 class="pay_legend">Укажите срок займа</h5>
	<?= $form->dropDownList($model, 'time', $aTimeValues, array('class'=>'hide','id'=>'time')); ?>

	<script type="text/javascript">
		/*jQuery(window).on('load',function() {
		 $("#slider .ui-slider-handle").tooltip({title: 'test', placement: 'bottom', trigger: 'manual', container: '#slider .ui-slider-handle'}).tooltip('show');
		 });*/

		$(function () {
			var oAmount = $('#amount');
			var oTime = $('#time');
			oAmount.selectToUISlider({
				labels: 7,
				tooltip: false
			});


			oAmount.change(function () {
				$(".cost.final_price").html(this.value);

			});
			oTime.change(function () {
				$(".cost.date").html(getDateToPayUntil(this.value));
			});


			oTime.selectToUISlider({
				labels: 7,
				tooltip: false
			});

		})
		;
	</script>
