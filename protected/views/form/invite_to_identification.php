<?php
/* @var FormController $this*/
/* @var InviteToIdentification $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Ваша заявка отправлена!
 * Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
 * Предлагаем дополнительно пройти видеорегистрацию
 */


$this->pageTitle=Yii::app()->name;

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id' => 'InviteToIdentificationForm',
	'action' => Yii::app()->createUrl('/form/'),
));
?>
<div class="row">
	<div class="row span12">
		<h3>Ваша заявка отправлена!</h3>
		<p>
			Ожидайте решения по займу. Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!
		</p>
	</div>
		<?php $oClientCreateForm->go = 1; ?>
		<?php echo $form->hiddenField($oClientCreateForm,'go',array('value'=>'1')); ?>
		<div class="clearfix"></div>
		<div class="form-actions">
			<? $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type' => 'primary',
				'label' => 'Пройти видеоидентификацию →',
			)); ?>
		</div>
	</div>
<?

$this->endWidget();
?>
