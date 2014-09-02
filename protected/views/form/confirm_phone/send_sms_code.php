<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 */

/*
 * Отправить код подтверждения на телефон и e-mail
 */
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<h4>Подтверждение контактных данных</h4>

Для завершения регистрации тебе необходимо подтвердить свой номер телефона и адрес электронной почты. <br />
<br />Твой номер телефона:<strong>+7<?= Yii::app()->clientForm->getSessionPhone() ?></strong>
<br />Твой адрес электронной почты:<strong><?= Yii::app()->clientForm->getSessionEmail() ?></strong> <br /><br />

<div class="clearfix"></div>
<div class="span12">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'     => 'sendCodes',
		'type'   => 'horizontal',
		'action' => Yii::app()->createUrl('/form/sendCodes'),
	));
	?>

	<div class="form-group row">
		<div class="col-xs-1 col-xs-offset-1">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'backButton',
				'buttonType' => 'link',
				'type'       => 'primary',
				'url'        => Yii::app()
						->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label'      => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="col-xs-6 col-xs-offset-3">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'sendSms',
				'type'       => 'success',
				'size'       => 'small',
				'buttonType' => 'submit',

				'label'      => 'Отправить коды подтверждения'
			));
			?>
		</div>
	</div>

	<?php
	$this->endWidget();
	?>
</div>