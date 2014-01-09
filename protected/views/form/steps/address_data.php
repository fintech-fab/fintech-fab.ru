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
		'hideErrorMessage' => true,
		'validateOnChange' => true,
		'validateOnSubmit' => true,
	),
	'action'               => Yii::app()->createUrl('/form'),
));

//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
//сделано во избежание навешивания кучи эвентов
Yii::app()->clientScript->registerScript('ajaxForm', '
		updateAjaxForm();
		');
Yii::app()->clientScript->registerScript('scrollAndFocus', '
		scrollAndFocus();
		', CClientScript::POS_LOAD);
?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>

<?php
$this->widget('FormProgressBarWidget', array('aSteps' => Yii::app()->clientForm->getFormWidgetSteps(), 'iCurrentStep' => Yii::app()->clientForm->getCurrentStep()));
?>
	<h4>Постоянная регистрация</h4>
	<div class="row">
		<div class="span5 offset6" id="reg_as_res">
			<?= $form->checkBoxRow($oClientCreateForm, 'address_reg_as_res', array('uncheckValue' => '0', 'inputType' => 'bootstrap.widgets.input.TbInputVertical')); ?>
		</div>
	</div>
	<div class="row">
		<div class="span6">
			<h5>Адрес регистрации</h5>

			<div class="row">
				<?= $form->select2Row($oClientCreateForm, 'address_reg_region', array('empty' => '', 'data' => Dictionaries::getRegions()) + $select2Js); ?>
				<div id="region-error" class="alert alert-error" style="display: none;"></div>
				<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city', SiteParams::getHintHtmlOptions($oClientCreateForm, 'address_reg_city')); ?>
				<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address', SiteParams::getHintHtmlOptions($oClientCreateForm, 'address_reg_address')); ?>
			</div>
		</div>
		<div class="span6">
			<div id="address_res">
				<h5>Фактический адрес проживания</h5>

				<div class="row">
					<?= $form->select2Row($oClientCreateForm, 'address_res_region', array('empty' => '', 'data' => Dictionaries::getRegions())); ?>
					<?= $form->textFieldRow($oClientCreateForm, 'address_res_city', SiteParams::getHintHtmlOptions($oClientCreateForm, 'address_res_city') + array('class' => 'span3')); ?>
					<?= $form->textFieldRow($oClientCreateForm, 'address_res_address', SiteParams::getHintHtmlOptions($oClientCreateForm, 'address_res_address') + array('class' => 'span3')); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="span12">
		<div class="form-actions row">

			<div class="span2">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'backButton',
					'buttonType'  => 'ajaxButton',
					'ajaxOptions' => array(
						'update' => '#formBody',
					),
					'url'         => Yii::app()
							->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
					'label'       => SiteParams::C_BUTTON_LABEL_BACK,
				)); ?>
			</div>

			<div class="span2 offset2">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'id'          => 'submitButton',
					'buttonType'  => 'ajaxSubmit',
					'ajaxOptions' => array(
						'type'   => 'POST',
						'update' => '#formBody',
					),
					'url'         => Yii::app()->createUrl('/form/ajaxForm'),
					'type'        => 'primary',
					'label' => SiteParams::C_BUTTON_LABEL_NEXT,
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
