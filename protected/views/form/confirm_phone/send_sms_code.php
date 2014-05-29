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

Для завершения регистрации Вам необходимо подтвердить свой номер телефона. <br /><br />Ваш номер телефона:
<strong>+7<?= Yii::app()->clientForm->getSessionPhone() ?></strong> <br />Ваш адрес электронной почты:
<strong><?= Yii::app()->clientForm->getSessionEmail() ?></strong> <br /><br />

<div class="clearfix"></div>
<div class="span12">
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'     => 'sendCodes',
		'type'   => 'horizontal',
		'action' => Yii::app()->createUrl('/form/sendCodes'),
	));
	?>

	<div class="form-actions row">
		<div class="span2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'backButton',
				'buttonType' => 'link',
				'url'        => Yii::app()
						->createUrl('/form/' . Yii::app()->clientForm->getCurrentStep()),
				'label'      => SiteParams::C_BUTTON_LABEL_BACK,
			)); ?>
		</div>
		<div class="span6">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'sendSms',
				'type'       => 'primary',
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