<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
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


	echo $form->dropDownList($model,'username',Yii::app()->adminKreddyApi->getFlexibleProduct());

	/*$this->widget('zii.widgets.jui.CJuiSlider', array(
		'id'             => 'slider',
		'value'          => 50, 'options' => array(
			'min'   => 1000,
			'max'   => 10000,
			'step'  => 500,
			'slide' => 'js:function(event, ui) {
			    $("#TextBoxId").val(ui.value);
			    $("#slider .ui-slider-handle").find(".tooltip-inner").html(ui.value);
			    $(".cost.final_price").html(ui.value);

			    var n = 7;
			    if(ui.value>=5000) n = 14;

				$(".cost.date").html(getDateToPayUntil(n));

			 }'
		), 'htmlOptions' => array(
			'style' => 'height:12px;',
		),
	));*/

	?>
	<script type="text/javascript">
		/*jQuery(window).on('load',function() {
			$("#slider .ui-slider-handle").tooltip({title: 'test', placement: 'bottom', trigger: 'manual', container: '#slider .ui-slider-handle'}).tooltip('show');
		});*/
	</script>



	<?= $form->hiddenField($model, 'username', array('id' => 'TextBoxId')); ?>

	<?php $this->endWidget(); ?>
</div>