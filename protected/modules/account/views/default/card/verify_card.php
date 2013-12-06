<?php
/* @var DefaultController $this */
/* @var AddCardForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Привязка банковской карты";
?>
	<h4>Привязка банковской карты</h4>

<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'add-card',
	'action'      => Yii::app()->createUrl('/account/verifyCard'),
	'htmlOptions' => array(
		'autocomplete' => 'off',
	)
));
?>
	<div class="alert alert-warning">
		<h4>Для завершения активации карты, необходимо точно ввести замороженную сумму (включая копейки).</h4>
		Узнать замороженную сумму можно одним из следующих способов:
		<ul>
			<li>SMS-банк</li>
			<li>Интернет-банк</li>
			<li>По телефону службы поддержки банка (номер телефона указан на обратной стороне карты)</li>
		</ul>
	</div>
<?= $form->textFieldRow($model, 'sCardVerifyAmount'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Подтвердить',
		)); ?>
	</div>

<?php

$this->endWidget();
//обновление страницы
Yii::app()->clientScript->registerScript('pageReload', '
	$(document).ready(function(){

            setInterval(function(){window.location.href=\'' . Yii::app()
		->createAbsoluteUrl(Yii::app()->request->requestUri) . '\';},60000);

        });
', CClientScript::POS_HEAD);
