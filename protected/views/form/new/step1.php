<?php
/**
 * @var FormController           $this
 * @var IkTbActiveForm           $form
 * @var ClientCreateFormAbstract $oClientCreateForm
 */

$this->pageTitle = 'Кредди - Сервис в твоем формате';
?>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => false,
	'type'                 => 'inline',
	'clientOptions'        => array(
		'hideErrorMessage' => true,
		'validateOnChange' => false,
		'validateOnSubmit' => false,
	),
	'action'               => Yii::app()->createUrl('/form'),
));
?>
<div class="col-lg-6 col-md-7">

	<?= $form->textFieldRow($oClientCreateForm, 'last_name', array('class' => 'slider_input freeze-alert')); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'first_name', array('class' => 'slider_input freeze-alert')); ?>

	<?= $form->textFieldRow($oClientCreateForm, 'third_name', array('class' => 'slider_input freeze-alert')); ?>

	<div class="clearfix"></div>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'  => 'submit',
		'type'        => 'danger',
		'label'       => 'Зарегистрироваться',

		'htmlOptions' => array(
			'class' => 'slider_submit freeze-alert',
		),
	)); ?>
</div>
<div class="col-lg-6 col-md-5 visible-lg visible-md hidden-xs hidden-sm">
	<div class="formErrors">
		<?= $form->errorSummary($oClientCreateForm, '', '', array('class' => 'alert alert-danger')); ?>
	</div>
</div>
<?php $this->endWidget(); ?>

<script>
	jQuery(document).ready(function () {
		var $freezed = jQuery('.freeze-alert');
		$freezed.on('change', function () {
			return alertFreeze();
		}).on('click', function () {
			return alertFreeze();
		}).on('keypress', function () {
			return alertFreeze();
		}).on('submit', function () {
			return alertFreeze();
		});
		function alertFreeze() {
			jQuery('.freeze-modal').fadeIn();
			return false;
		}
	});
</script>
<div class="freeze-modal" style="font-family: Tahoma sans-serif; font-size: 18px; display: none; position: absolute; width: 500px; min-height: 400px; top: -5%; padding: 20px; text-align: center; background-color: white; border: 3px solid #808080; z-index: 99999;">
	<br> <br> <br> <br> Мы готовим наш сервис к глобальным изменениям <br>и временно приостановили возможность новых
	подключений. <br><br> Приносим извинения за доставленные неудобства!
	<!--	<br><br>Когда всё будет готово, мы обязательно оповестим тебя!-->
</div>
