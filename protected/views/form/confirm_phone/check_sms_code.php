<?php
/* @var FormController $this */
/* @var ClientConfirmPhoneViaSMSForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 */

/*
 * Ввести код подтверждения из SMS
 */
?>
<h4>Подтверждение номера телефона</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                     => "checkSmsCode",
	'enableClientValidation' => true,
	'htmlOptions'            => array(
		'class' => "span10",
	),
	'clientOptions'          => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'                 => Yii::app()->createUrl('/form/checkSmsCode'),
));
?>
<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<div id="alertsmssent" class="alert in alert-success"><?= Dictionaries::C_SMS_SUCCESS_NUM; ?>
	&nbsp;+7<?= Yii::app()->clientForm->getSmsSentPhone(); ?></div>


<div class="clearfix"></div>    <label>Введите код из SMS:</label>
<?= $form->textField($oClientCreateForm, 'sms_code', array('class' => 'span4')); ?>
<?= $form->error($oClientCreateForm, 'sms_code'); ?>

<div class="clearfix"></div>
<div class="span12">

	<div class="form-actions row">
		<div class="span2 offset1">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'backButton',
				'buttonType' => 'link',
				'url'        => Yii::app()
						->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label'      => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="span2 offset2">

			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>


	<?php
	$this->endWidget();
	?>
</div>

<div class="clearfix"></div>

<div class="alert in alert-warning" style="font-size: 12pt;">
	После ввода кода из SMS-сообщения необходимо пройти идентификацию для завершения регистрации.
</div>
