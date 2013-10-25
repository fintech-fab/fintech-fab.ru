<?php
/* @var DefaultController $this */
/* @var ChangePassportDataForm $oChangePassportForm */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Изменение паспортных данных";
?>
<h4>Изменение паспортных данных</h4>



<?php
Yii::app()->clientScript->registerScript('formName', '
var oOldPassportData = $("#oldPassportData");

var passportNotChanged = $("#passport_not_changed").find("input[type=checkbox]");
passportNotChanged.change(function () {
	/*
	 * Проверяем, установлен или снят чекбокс, и либо убираем и дизейблим, либо наоборот соответствующие части формы
	 * Обязательно убираем класс error/success и очищаем поля при этом
	 */

	if (!passportNotChanged.prop("checked")) {
		oOldPassportData.find(":input").attr("disabled", false).removeClass("disabled").parents(".control-group").removeClass("error success");
		oOldPassportData.show();
	} else {
		oOldPassportData.find(":input").attr("disabled", "disabled").addClass("disabled").val("").parents(".control-group").removeClass("error").addClass("success").find(".help-inline").hide();
		oOldPassportData.hide();
	}
	changeReason.change();
});

	var changeReason = jQuery("#' . get_class($oChangePassportForm) . '_passport_change_reason");
	var changePassAdditionalFields = $("#changePassportTicketDepartment");
	changeReason.change(function()
	{

		//если причина смены ==2 (утеря или кража)
		if(changeReason.find(":selected").val()==2){
			//отображаем дополнительные поля
			changePassAdditionalFields.find(":input").attr("disabled", false).removeClass("disabled").parents(".control-group").removeClass("error success");
			changePassAdditionalFields.show();

		} else {
			//прячем дополнительные поля
				changePassAdditionalFields.find(":input").attr("disabled", "disabled").addClass("disabled").val("").parents(".control-group").removeClass("error").addClass("success").find(".help-inline").hide();
				changePassAdditionalFields.hide();
		}

	});

	//после загрузки страницы прячем форму ввода данных старого паспорта, если стоит чекбокс
	passportNotChanged.change();
	changeReason.change();//вызываем сразу после загрузки страницы
', CClientScript::POS_LOAD);

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => 'passport-form',
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
	'clientOptions'        => array(
		'validateOnChange' => true,
	),
	'action'               => Yii::app()->createUrl('/account/changePassport'),
));
?>
<div id="oldPassportData">
	<div class="control-group">
		<div class="row">
			<div class="span5">
				<h5>Старый паспорт (если паспорт менялся)</h5>
			</div>
		</div>
		<div class="row">
			<div class="span3">
				<?= $form->labelEx($oChangePassportForm, 'old_passport_number', array('class' => 'control-label')); ?>
				<div class="controls"><?= $form->maskedTextField($oChangePassportForm, 'old_passport_series', '9999', array('style' => 'width: 40px;', 'size' => '4', 'maxlength' => '4')); ?></div>
			</div>
			<div class="span2">
				<span>/</span>
				<?= $form->maskedTextField($oChangePassportForm, 'old_passport_number', '999999', array('style' => 'width: 60px;', 'size' => '6', 'maxlength' => '6')); ?>
			</div>
		</div>
		<div class="row">
			<div class="span5">
				<div style="margin-left: 180px;">
					<?= $form->error($oChangePassportForm, 'old_passport_series'); ?>
					<?= $form->error($oChangePassportForm, 'old_passport_number'); ?></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span5">
			<div>
				<?= $form->dropDownListRow($oChangePassportForm, 'passport_change_reason', Dictionaries::$aChangePassportReasons); ?>
				<div id="changePassportTicketDepartment">
					<?= $form->textFieldRow($oChangePassportForm, 'passport_change_ticket'); ?>
					<?= $form->textFieldRow($oChangePassportForm, 'passport_change_department'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="passport_not_changed">
	<?= $form->checkBoxRow($oChangePassportForm, 'passport_not_changed', array('uncheckValue' => '0')); ?>
</div>
<div class="clearfix"></div>
<div class="row">
	<div class="span5">
		<h5>Паспорт</h5>
		<?= $form->textFieldRow($oChangePassportForm, 'last_name'); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'first_name'); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'third_name'); ?>
	</div>
</div>
<div class="control-group">
	<div class="row">
		<div class="span3">
			<?= $form->labelEx($oChangePassportForm, 'passport_number', array('class' => 'control-label')); ?>
			<div class="controls"><?= $form->maskedTextField($oChangePassportForm, 'passport_series', '9999', array('style' => 'width: 40px;', 'size' => '4', 'maxlength' => '4')); ?></div>
		</div>
		<div class="span2">
			<span>/</span>
			<?= $form->maskedTextField($oChangePassportForm, 'passport_number', '999999', array('style' => 'width: 60px;', 'size' => '6', 'maxlength' => '6')); ?>
		</div>
	</div>
	<div class="row">
		<div class="span5">
			<div style="margin-left: 180px;">
				<?= $form->error($oChangePassportForm, 'passport_series'); ?>
				<?= $form->error($oChangePassportForm, 'passport_number'); ?>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div>

<div class="row">
	<div class="span5">
		<?= $form->dateMaskedRow($oChangePassportForm, 'passport_date'); ?>
		<?= $form->fieldMaskedRow($oChangePassportForm, 'passport_code', array('mask' => '999-999', 'size' => '7', 'maxlength' => '7')); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'passport_issued'); ?>

		<h5>Адрес регистрации</h5>
		<?= $form->select2Row($oChangePassportForm, 'address_reg_region', array('empty' => '', 'data' => Dictionaries::getRegions())); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'address_reg_city'); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'address_reg_address'); ?>

		<h5>Второй документ</h5>
		<?= $form->dropDownListRow($oChangePassportForm, 'document', Dictionaries::$aDocuments, array('empty' => '')); ?>
		<?= $form->textFieldRow($oChangePassportForm, 'document_number'); ?>

	</div>
</div>
<div class="row">
	<div class="span8">
		<?= $form->checkBoxRow($oChangePassportForm, 'confirm', array('uncheckValue' => '0')); ?>
	</div>
</div>


<div class="clearfix"></div>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType' => 'submit',
		'type'       => 'primary',
		'size'       => 'small',
		'label'      => 'Отправить заявку',
	)); ?>
</div>

<?php
$this->endWidget();

//при изменении типа документа заново валидировать поле с номером документа.

Yii::app()->clientScript->registerScript('validate_document_number', '

	jQuery("#' . get_class($oChangePassportForm) . '_document").change(function()
	{
		var form=$("#' . get_class($oChangePassportForm) . '");
        var settings = form.data("settings");
        $.each(settings.attributes, function () {
	        if(this.name == "' . get_class($oChangePassportForm) . '[document_number]"){
	            this.status = 2; // force ajax validation
	        }
	    });
	    form.data("settings", settings);

	    // trigger ajax validation
	    $.fn.yiiactiveform.validate(form, function (data) {
	        $.each(settings.attributes, function () {

				if(this.name == "' . get_class($oChangePassportForm) . '[document_number]"){
	                $.fn.yiiactiveform.updateInput(this, data, form);
	            }
	        });
	    });
	});
', CClientScript::POS_LOAD);

?>


<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'privacy')); ?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4>Условия обслуживания и передачи информации</h4>
</div>

<div class="modal-body">
	<p>Заполняя и отправляя в адрес ООО «Финансовые Решения» (далее – Общество) данную форму анкеты и/или форму анкеты,
		заполненную мною дистанционным способом, я подтверждаю правильность указанных мною персональные данных,
		принадлежащих лично мне, а так же выражаю свое согласие на обработку (в том числе сбор, систематизацию,
		проверку, уточнение, изменение, обновление, использование, распространение (в том числе передачу третьим лицам),
		обезличивание, блокирование, уничтожение персональных данных) ООО «Финансовые Решения», место нахождения:
		Москва, Гончарная наб. д.1 стр.4, своих персональных данных, содержащихся в настоящей Анкете или переданных мною
		Обществу дистанционным способом. Персональные данные подлежат обработке (в том числе с использованием средств
		автоматизации) в целях принятия решения о предоставлении микрозайма, заключения, изменения, расторжения,
		дополнения, а также исполнения договоров микрозайма, дополнительных соглашений, заключенных или заключаемых
		впоследствии мною с ООО «Финансовые Решения». Настоящее согласие действует до момента достижения цели обработки
		персональных данных. Отзыв согласия на обработку персональных данных производится путем направления
		соответствующего письменного заявления Обществу по почте. Так же выражаю свое согласие на информирование меня
		Обществом о размерах микрозайма, полной сумме, подлежащей выплате, информации по продуктам или рекламной
		информации Общества по телефону, электронной почте, SMS – сообщениями.</p>

	<p>Направляя в ООО «Финансовые Решения» данную Анкету/или форму анкеты, заполненную мною дистанционным способом
		выражаю свое согласие на получение и передачу ООО «Финансовые Решения» (Общество) информации, предусмотренной
		Федеральным законом № 218 от 30.12.2004 "О кредитных историях", о своей кредитной истории в соответствующее бюро
		кредитных историй (Бюро кредитных историй определяет Общество по своему усмотрению). Список бюро указан на сайте
		Общества <a href="http://kreddy.ru/" target="_blank">www.kreddy.ru</a>, а также с тем, что в случае
		неисполнения, ненадлежащего исполнения и/или задержки исполнения мною своих обязательств по договорам
		микрозайма, заключенных с Обществом, Общество вправе раскрыть информацию об этом любым лицам (в т.ч.
		неопределенному кругу лиц) и любым способом (в т.ч. путем опубликования в средствах массовой информации).</p>

	<p>Направляя/подписывая в ООО «Финансовые Решения» данную форму Анкеты или анкету, заполненную мною дистанционным
		способом, подтверждаю, что ознакомлен с правилами предоставления микрозайма, со всеми условиями предоставления
		микрозайма. Также подтверждаю, что номер мобильного телефона, указанный в анкете, принадлежит лично мне.
		Ответственность за неправомерное использование номера мобильного телефона лежит на мне.</p>
</div>

<div class="modal-footer">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'       => 'Закрыть',
		'url'         => '#',
		'htmlOptions' => array('data-dismiss' => 'modal'),
	)); ?>
</div>

<?php $this->endWidget(); ?>
