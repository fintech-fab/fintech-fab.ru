<?php
/* @var DefaultController $this */
/* @var ChangeNumericCodeForm $oChangeNumericCodeForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение цифрового кода";
?>
<h4>Изменение цифрового кода</h4>



<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'numeric-code-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/account/changeNumericCode'),
));
?>
<div class="row">
	<div class="span5">
		<?= $form->textFieldRow($oChangeNumericCodeForm, 'numeric_code'); ?>
	</div>
</div>

<div class="clearfix"></div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Отправить заявку',
	)); ?>
</div>

<?php
$this->endWidget();