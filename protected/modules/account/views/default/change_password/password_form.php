<?php
/* @var DefaultController $this */
/* @var ChangePasswordForm $oChangePasswordForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение пароля";
?>
<h4>Изменение пароля</h4>



<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'password-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/account/changePassword'),
));
?>
<div class="row">
	<div class="span5">
		<?= $form->passwordFieldRow($oChangePasswordForm, 'old_password'); ?>
		<?= $form->passwordFieldRow($oChangePasswordForm, 'password'); ?>
		<?= $form->passwordFieldRow($oChangePasswordForm, 'password_repeat'); ?>
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