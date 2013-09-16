<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = array(
	array('Выбор пакета', 1),
	array('Знакомство', 2),
	array('Заявка на займ', 5, 3)
);

/*
 * Отправить код подтверждения на телефон
 */
?>

<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>

	<div class="span12">
		Для завершения регистрации Вам необходимо подтвердить свой телефон. <br /> Ваш телефон:
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
	</div>

	<?php $this->widget('YaMetrikaGoalsWidget', array(
		'iDoneSteps'    => Yii::app()->clientForm->getCurrentStep(),
		'iSkippedSteps' => 2,
	)); ?>
</div>
