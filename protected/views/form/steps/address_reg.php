<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form#next'),
));

//TODO перенести сообщения куда-нибудь, минимизировать использование JS
if (SiteParams::getIsIvanovoSite()) {
	$select2Js = array(
		'onchange' => 'js: if($(this).find(":selected").attr("value")!=37){'
			. '$("#region-error").html('
			. '"Внимание! Вы выбрали не Ивановскую область, и после заполнения заявки не сможете оформить займ,'
			. ' выбранный на первом шаге! В личном кабинете Вам будут доступны стандартные пакеты займов КРЕДДИ.").show();'
			. '} else {'
			. '$("#region-error").html("").hide();'
			. '}'
	);
} else {
	$select2Js = array();
}
?>

<h4 id="addressHeading">Постоянная регистрация</h4>

<div class="span5">
	<h5>Адрес регистрации</h5>

	<?= $form->select2Row($oClientCreateForm, 'address_reg_region', array('empty' => '', 'data' => Dictionaries::getRegions()) + $select2Js); ?>
	<div id="region-error" class="alert alert-error" style="display: none;"></div>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city'); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address'); ?>
	<div id="reg_as_res">
		<?= $form->checkBoxRow($oClientCreateForm, 'address_reg_as_res' + array('uncheckValue' => '0')); ?>
	</div>
	<div id="address_res">
		<h5>Фактический адрес проживания</h5>

		<?= $form->select2Row($oClientCreateForm, 'address_res_region', array('empty' => '', 'data' => Dictionaries::getRegions())); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'address_res_city', array('class' => 'span3')); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'address_res_address', array('class' => 'span3')); ?>
	</div>
</div>

<div class="span5 offset1">
	<h5>Контакты родственников/друзей</h5>
	<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio', array('class' => 'span3')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone', array('class' => 'span3')); ?>

	<h5>Дополнительный контакт<br />(повышает вероятность одобрения)</h5>
	<?= $form->textFieldRow($oClientCreateForm, 'friends_fio', array('class' => 'span3')); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone', array('class' => 'span3')); ?>
</div>

<div class="clearfix"></div>
<div class="row span10">
	<div class="form-actions">
		<div class="row">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'id'         => 'submitButton',
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => 'Далее',
			)); ?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>

