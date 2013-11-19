<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form IkTbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
	'Contact',
);
?>

	<h1>Contact Us</h1>

<?php if (Yii::app()->user->hasFlash('contact')): ?>

	<div class="alert alert-success">
		<?php echo Yii::app()->user->getFlash('contact'); ?>
	</div>

<?php else: ?>

	<p>
		If you have business inquiries or other questions, please fill out the following form to contact us. Thank
		you. </p>

	<div class="form">

		<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
			'id'                     => 'contact-form',
			'enableClientValidation' => true,
			'clientOptions'          => array(
				'validateOnSubmit' => true,
			),
		)); ?>

		<p class="note">Fields with <span class="required">*</span> are required.</p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->textFieldRow($model, 'name', array('style' => 'width: 400px;')); ?>
		</div>

		<div class="row">
			<?php echo $form->textFieldRow($model, 'email', array('style' => 'width: 400px;')); ?>
		</div>

		<div class="row">
			<?php echo $form->textFieldRow($model, 'phone', array('style' => 'width: 400px;')); ?>
		</div>

		<div class="row">
			<?php echo $form->textFieldRow($model, 'subject', array('size' => 60, 'maxlength' => 128, 'style' => 'width: 400px;')); ?>
		</div>

		<div class="row">
			<?php echo $form->textAreaRow($model, 'body', array('rows' => 10, 'cols' => 100, 'style' => 'width: 400px;')); ?>
		</div>

		<?php if (CCaptcha::checkRequirements()): ?>
			<div class="row">
				<?php echo $form->labelEx($model, 'verifyCode'); ?>
				<div>
					<?php $this->widget('CCaptcha'); ?>
					<?php echo $form->textField($model, 'verifyCode'); ?>
				</div>
				<div class="hint">Please enter the letters as they are shown in the image above. <br />Letters are not
					case-sensitive.
				</div>
				<?php echo $form->error($model, 'verifyCode'); ?>
			</div>
		<?php endif; ?>

		<div class="form-actions">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'send',
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Отправить',
			));
			?>
		</div>

		<?php $this->endWidget(); ?>

	</div><!-- form -->

<?php endif; ?>