<?php
/* @var FormController $this*/
/* @var ClientSendForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Ваша заявка отправлена!
 * Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
 * Предлагаем дополнительно пройти видеорегистрацию
 */


$this->pageTitle=Yii::app()->name;

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id' => 'InviteToIdentification',//get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'action' => Yii::app()->createUrl('/form/'),
));
?>
<div class="row">
	<?php $this->widget('StepsBreadCrumbs',array(
		'curStep'=>6,
	)); ?>

	<div class="row span5">
		<h3>Ваша заявка отправлена!</h3>
		<p>
			Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
		</p>
	</div>

		<div class="clearfix"></div>
		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => 'Далее →',
			)); ?>
		</div>
	</div>
<?

$this->endWidget();
?>
