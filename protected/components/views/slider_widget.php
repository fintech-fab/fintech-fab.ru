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
	//TODO сделать нормальную модель
	$model = new LoginForm();


	echo $form->dropDownList($model, 'username', $aSelectValues, array('class'=>'hide'));

	?>
	<script type="text/javascript">
		/*jQuery(window).on('load',function() {
		 $("#slider .ui-slider-handle").tooltip({title: 'test', placement: 'bottom', trigger: 'manual', container: '#slider .ui-slider-handle'}).tooltip('show');
		 });*/

		$(function () {
			var oSelect = $('select');
			oSelect.selectToUISlider({
				labels: 7,
				tooltip: false
			});


			oSelect.change(function () {
				$(".cost.final_price").html(this.value);
				var n = 7;
				if (this.value >= 5000) n = 14;

				$(".cost.date").html(getDateToPayUntil(n));
			});


		})
		;
	</script>



	<?= $form->hiddenField($model, 'username', array('id' => 'TextBoxId')); ?>

	<?php $this->endWidget(); ?>
</div>