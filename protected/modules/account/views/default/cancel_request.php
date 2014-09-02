<?php
/* @var DefaultController $this */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Отказаться/изменить текущий тариф";

?>
<h4>Отказаться/изменить текущий тариф</h4>

<strong>Статус:</strong> <?= Yii::app()->adminKreddyApi->getStatusMessage() ?>
&nbsp;<?= Yii::app()->adminKreddyApi->getChannelNameForStatus(); ?>
<br /><br />
<div class="alert in alert-block alert-warning">
	Ты передумал подключать текущий тариф?
	<?php
	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'action'               => Yii::app()->createUrl('account/cancelRequest'),
		'method'               => 'post',
		'enableAjaxValidation' => false,
		'clientOptions'        => array(
			'validateOnSubmit' => false,
		),
	));
	?>


	<div class="form-actions">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => 'submitButton',
			'buttonType'  => 'submit',
			'icon'        => "icon-ok icon-white",
			'type'        => 'primary',
			'label'       => 'Да',
			'htmlOptions' => array(
				'name'    => 'cancel',
				'value'   => 1,
				'confirm' => "Ты действительно хочешь отменить подключение текущего тарифа?\n
					ВНИМАНИЕ! Воспользоваться отменой подключения можно не более одного раза в месяц.",
			),
		)); ?>
		&nbsp;
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
				'label' => 'Нет',
				'icon'  => "icon-remove icon-white",
				'type'  => 'primary',
				'url'   => Yii::app()->createUrl('account/'),
			)
		);

		$this->endWidget();
		?>
	</div>
</div>
<div class="clearfix"></div>