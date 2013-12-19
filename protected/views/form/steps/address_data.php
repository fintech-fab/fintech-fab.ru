<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

?>
<?php
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

?>
	<h4>Адрес регистрации</h4>
	<div class="span5">
		<h5>Адрес регистрации</h5>

		<?= $form->select2Row($oClientCreateForm, 'address_reg_region', array('empty' => '', 'data' => Dictionaries::getRegions()) + $select2Js); ?>
		<div id="region-error" class="alert alert-error" style="display: none;"></div>
		<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city'); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address'); ?>
		<div id="reg_as_res">
			<?= $form->checkBoxRow($oClientCreateForm, 'address_reg_as_res', array('uncheckValue' => '0')); ?>
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
<?php $this->endWidget();

Yii::app()->clientScript->registerScript('addressScript', '
	var oAddressRes = $("#address_res");

	if (!$("#reg_as_res").find("input[type=checkbox]").prop("checked")) {
		oAddressRes.find(":input").attr("disabled", false).removeClass("disabled");
		oAddressRes.show();
		oAddressRes.find("label").append("<span class=\"required\">*</span>");
	} else {
		oAddressRes.find(":input").attr("disabled", "disabled").addClass("disabled").parents(".control-group").addClass("success").val("");
		oAddressRes.hide();
		oAddressRes.find("label").append("<span class=\"required\">*</span>");
	}

	/**
	* вешаем на чекбокс обработчик, чтобы по смене сразу валидировать и менять состояние формы
	*/

	jQuery("#reg_as_res").find("input[type=checkbox]").change(function () {
		/*
		* Проверяем, установлен или снят чекбокс, и либо убираем и дизейблим, либо наоборот соответствующие части формы
		* Обязательно убираем класс error/success и очищаем поля при этом
		*/
		if (!$("#reg_as_res").find("input[type=checkbox]").prop("checked")) {
			oAddressRes.find(":input").attr("disabled", false).removeClass("disabled").parents(".control-group").removeClass("error success");
			oAddressRes.show();
		} else {
			oAddressRes.find(":input").attr("disabled", "disabled").addClass("disabled").val("").parents(".control-group").removeClass("error").addClass("success").find(".help-inline").hide();
			oAddressRes.hide();
		}

	});
', CClientScript::POS_READY);