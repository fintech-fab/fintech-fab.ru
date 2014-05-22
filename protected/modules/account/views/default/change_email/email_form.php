<?php
/* @var DefaultController $this */
/* @var ChangeEmailForm $oChangeEmailForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение адреса электронной почты";
?>
	<h4>Изменение адреса электронной почты</h4>



<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'numeric-code-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/account/changeEmail'),
));
?>
	<div class="row">
		<div class="span5">
			<?= $form->textFieldRow($oChangeEmailForm, 'email'); ?>
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Отправить',
		)); ?>
	</div>

<?php
$this->endWidget();