<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 * @var string $sErrorMessage
 */

/*
 * Отправить код подтверждения на телефон (ошибка)
 */
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

Для завершения регистрации Вам необходимо подтвердить свой номер телефона. <br /> Ваш номер телефоан:
<strong>+7<?= Yii::app()->clientForm->getSessionPhone() ?></strong> <br /><br />

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'sendSmsCode',
	'action' => Yii::app()->createUrl('/form/sendSmsCode'),
));

$this->widget('bootstrap.widgets.TbButton', array(
	'id'         => 'sendSms',
	'type'       => 'primary',
	'size'       => 'small',
	'buttonType' => 'submit',
	'label'      => 'Отправить на +7' . Yii::app()->clientForm->getSessionPhone() . ' SMS с кодом подтверждения',
));

$this->endWidget();
?>

<div class="alert in alert-block fade alert-error" id="errorMessage">
	<?= $sErrorMessage ?>
</div>
