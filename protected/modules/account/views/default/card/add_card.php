<?php
/* @var DefaultController $this */
/* @var AddCardForm $model */
/* @var IkTbActiveForm $form */
/* @var $sError */

$this->pageTitle = Yii::app()->name . " - Привязка банковской карты";

// путь до соответствующей картинки:
$sImagePath = (!empty($model->iCardType) && in_array($model->iCardType, array_keys(Dictionaries::$aCardTypes))) ? ('url(\'' . Yii::app()
		->getBaseUrl() . '/static/img/bankcard/icon-' . mb_convert_case(Dictionaries::$aCardTypes[$model->iCardType], MB_CASE_LOWER, 'utf-8') . '.gif\') ') : 'none';

// если Клиент привязывает карту первый раз - выводим предупреждение в модальном окне
if (Yii::app()->adminKreddyApi->getIsFirstAddingCard()):
	?>
	<div id="card_warning" class="modal fade">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h4>Внимание!</h4>
		</div>

		<div class="modal-body">
			<?= $this->renderPartial('card/card_add_warning', null, true); ?>
		</div>

		<div class="modal-footer">
			<a data-dismiss="modal" class="btn" id="yw0" href="#">Закрыть</a></div>

	</div>
	<script>
		jQuery("#card_warning").modal('show');
	</script>
<?php
endif;
?>
	<h4>Привязка банковской карты</h4>

<?php if (!empty($sError)) { ?>
	<div class="alert alert-error"><?= $sError ?></div>
<?php } ?>


<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'          => 'add-card',
	'type' => 'horizontal',
	'action'      => Yii::app()->createUrl('/account/addCard'),
	'htmlOptions' => array(
		'autocomplete' => 'off',
	)
));
?>
<?= $form->errorSummary($model) ?>

	<div class="alert alert-warning">
		<p style="color: red;">Уважаемые клиенты! По техническим причинам банковские карты VISA временно отвязаны от
			всех аккаунтов.<br /> Вы можете привязать другую карту (MasterCard, Maestro) или получить займ на счет
			Вашего мобильного телефона (Мегафон, МТС, Билайн, Теле 2).<br /> Приносим свои извинения за доставленные
			неудобства.</p>
	</div>

	<div class="alert alert-warning" style="color: #000000 !important">
		<?= $this->renderPartial('card/card_add_warning', null, true); ?>
	</div>

<?= $form->hiddenField($model, 'iCardType') ?>
<?php if (Yii::app()->adminKreddyApi->isCardVerifyNeedAdditionalFields()) { ?>
	<?= $form->textFieldRow($model, 'sEmail', array('maxlength' => '50')); ?>
	<?= $form->textFieldRow($model, 'sAddress', array('maxlength' => '50')); ?>
	<?= $form->textFieldRow($model, 'sCity', array('maxlength' => '50')); ?>
	<?= $form->textFieldRow($model, 'sZipCode', array('maxlength' => '10')); ?>
<?php } ?>

	<div style="background: url('/static/img/bankcard/cc-template.png'); width: 550px; height: 280px; margin-bottom: 15px;">
		<?= $form->textField($model, 'sCardPan', array('class' => 'card', 'maxlength' => '20', "style" => "position: relative; top: 136px; left: 40px; width: 183px;", 'placeholder' => "1234567812345678")); ?>

		<?= $form->maskedTextField($model, 'sCardValidThru', ' 99 / 99 ', array('class' => 'card', "style" => "position: relative; top: 164px; left: -29px; width: 53px;")); ?>

		<?= $form->maskedTextField($model, 'sCardCvc', '999', array('class' => 'card', "style" => "position: relative; top: 132px; left: 132px; width: 32px;", 'size' => '3', 'maxlength' => '3')); ?>

		<?= $form->textField($model, 'sCardHolderName', array('class' => 'card', "style" => "position: relative; top: 191px; left: -276px; width: 183px;", 'placeholder' => "MR. CARDHOLDER")); ?>

		<div style="position: relative; top: 130px; left: 257px; width: 82px; height: 44px; background: <?= $sImagePath ?>" id="cardType"></div>
	</div>
	<div class="confirmations">
		<?= $form->checkBoxRow($model, 'bConfirm'); ?>

		<span id="bAgreeDiv" data-content='<?= AdminKreddyApiComponent::C_CARD_AGREEMENT; ?>' data-toggle='popover' data-trigger='hover' data-placement="top">
		<?= $form->checkBoxRow($model, 'bAgree'); ?>
	</span>
	</div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Привязать карту',
		));
		?>
	</div>
<?php

$this->endWidget();

Yii::app()->clientScript->registerCss('formstyle', '
input[type="text"].card {
background-color: transparent;
border: 0px;
height: 14px;
}

div.confirmations {
margin-left: -180px;
');

$sScript = 'oCardPan = $("#' . get_class($model) . '_sCardPan");
oCardTypeField = $("#' . get_class($model) . '_iCardType");
var regexp;

oCardPan.bind( "change click keydown keyup blur", function() {
	if(oCardPan.val() == ""){
		return;
	}

	$("#cardType").css("backgroundImage", "none");
	oCardTypeField.val("-1");';

foreach (Dictionaries::$aCardTypes as $iKey => $oType) {
	$sScript .= '
	regexp = ' . Dictionaries::$aCardTypesRegexp[$iKey] . ';
	if(regexp.test($.trim(oCardPan.val()))) {
		oCardTypeField.val(' . $iKey . ');
		$("#cardType").css("backgroundImage", "url(' . Yii::app()
			->getBaseUrl() . '/static/img/bankcard/icon-' . mb_convert_case($oType, MB_CASE_LOWER, 'utf-8') . '.gif)");
	}';
}

$sScript .= '
});
';

Yii::app()->clientScript->registerScript('cardType', $sScript, CClientScript::POS_END);
