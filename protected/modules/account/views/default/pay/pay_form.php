<?php
/* @var DefaultController $this */
/* @var PayForm $oPayForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Оплата с банковской карты";
?>
<h4>Оплата с банковской карты</h4>


<?php if (Yii::app()->adminKreddyApi->getIsClientCardExists()) { ?>
	<?php $form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => 'password-form',
		'enableAjaxValidation' => true,
		'type'                 => 'horizontal',
		'clientOptions'        => array(
			'validateOnChange' => true,
		),
		'action'               => Yii::app()->createUrl('/account/pay'),
	));
	?>
	<div class="row">
		<div class="span8">
			<div class="alert alert-info">
				Для оплаты будет использована текущая привязанная банковская
				карта <?= Yii::app()->adminKreddyApi->getBankCardPan(); ?>
			</div>
			<?php if (!isset($oPayForm->full_pay)) {
				$oPayForm->full_pay = 1;
			} ?>
			<?= $form->radioButtonListRow($oPayForm, 'full_pay', [1 => 'оплатить полностью', 0 => 'оплатить частично'], ['label' => false]) ?>
			<div id="sum" style="display: none;">
				<?= $form->textFieldRow($oPayForm, 'sum'); ?>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Оплатить задолженность',
		)); ?>
	</div>

	<?php
	$this->endWidget();

} else {
	?>
	<div class="alert alert-error">
		У тебя нет привязанной банковской карты! Для оплаты сначала привяжи банковскую карту.
	</div>
<?php
}

$sFormName = get_class($oPayForm);

Yii::app()->clientScript->registerScript('payForm', '
	function onChange(){
		if(jQuery("#' . $sFormName . '_full_pay input[value=1]:checked").length){
            jQuery("#sum").hide();
		}

		if(jQuery("#' . $sFormName . '_full_pay input[value=0]:checked").length){
            jQuery("#sum").show();
		}
	}

	onChange();

	jQuery("#' . $sFormName . '_full_pay input").on("change",function(){
		onChange();
	});
	', CClientScript::POS_READY);

?>

