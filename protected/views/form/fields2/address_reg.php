<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

?>
<?php
$htmlOptions = array(
	'errorOptions' => array(
		'afterValidateAttribute' => 'js: function(html){
			addressOk = false;
			if($("#personalData").hasClass("in")) $("#personalData").collapse("hide");
			if($("#passportData").hasClass("in")) $("#passportData").collapse("hide");
			var formName="' . get_class($oClientCreateForm) . '";
			var aAttrs = Array(
				"address_reg_region",
				"address_reg_city",
				"address_reg_address",
				"address_res_region",
				"address_res_city",
				"address_res_address",
				"relatives_one_fio",
				"relatives_one_phone"
			);
			var bFlag = true;
			var sAttrName;
			for(i=0;i<aAttrs.length;i++)
			{
				sAttrName = formName +"_"+aAttrs[i];
				if(!$("#"+sAttrName).parents(".control-group").hasClass("success")){
					bFlag = false;
				}
			}
			if(bFlag){
				$("#jobInfoHeading").attr("href","#jobInfo");
				if(!$("#jobInfo").hasClass("in")) $("#jobInfo").collapse("show");
				addressOk = true;
			}
		}'
	)
);

$checkBoxHtmlOptions = array_merge($htmlOptions, array(
	'id' => 'regAsResCheckBox',
));
?>
<div class="span5">
	<h5>Адрес регистрации</h5>
	<?= $form->dropDownListRow($oClientCreateForm, 'address_reg_region', Dictionaries::getRegions(), array('empty' => '', 'class' => 'span3') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city', $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address', $htmlOptions); ?>

	<?= $form->checkBoxRow($oClientCreateForm, 'address_reg_as_res', $checkBoxHtmlOptions); ?>
	<div id="address_res">
		<h5>Фактический адрес проживания</h5>
		<?= $form->dropDownListRow($oClientCreateForm, 'address_res_region', Dictionaries::getRegions(), array('class' => 'span3', 'empty' => '') + $htmlOptions); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'address_res_city', array('class' => 'span3') + $htmlOptions); ?>
		<?= $form->textFieldRow($oClientCreateForm, 'address_res_address', array('class' => 'span3') + $htmlOptions); ?>
	</div>
</div>

<div class="span5 offset1">
	<h5>Контактное лицо</h5>
	<?= $form->textFieldRow($oClientCreateForm, 'relatives_one_fio', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'relatives_one_phone', array('class' => 'span3') + $htmlOptions); ?>

	<h5>Дополнительный контакт<br />(повышает вероятность одобрения)</h5>
	<?= $form->textFieldRow($oClientCreateForm, 'friends_fio', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'friends_phone', array('class' => 'span3') + $htmlOptions); ?>
</div>