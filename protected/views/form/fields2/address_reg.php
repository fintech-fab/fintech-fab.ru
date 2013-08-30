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
			var iCount = 0;
			var sAttrName;
			for(i=0;i<aAttrs.length;i++)
			{
				sAttrName = formName +"_"+aAttrs[i];
				if(!$("#"+sAttrName).parents(".control-group").hasClass("success")){
					iCount++;
				}
			}
			if(iCount<=1){
				$("#jobInfoHeading").attr("href","#jobInfo");
				if(!$("#jobInfo").hasClass("in")){
					$("#jobInfo").collapse("show");
				}
				if($("#passportData").hasClass("in")){
					$("#passportData").collapse("hide");
				}
				$("#jobInfo").find(":input").prop("disabled",false);
				$("#sendHeading").removeClass("disabled cursor-default");
				if(iCount==0){
					addressOk = true;
				}
			}
		}'
	)
);

?>
<div class="span5">
	<h5>Адрес регистрации</h5>

	<?= $form->select2Row($oClientCreateForm, 'address_reg_region', array('empty' => '', 'data' => Dictionaries::getRegions()) + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_city', $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'address_reg_address', $htmlOptions); ?>
	<div id="reg_as_res">
		<?= $form->checkBoxRow($oClientCreateForm, 'address_reg_as_res', $htmlOptions + array('uncheckValue' => '0')); ?>
	</div>
	<div id="address_res">
		<h5>Фактический адрес проживания</h5>

		<?= $form->select2Row($oClientCreateForm, 'address_res_region', array('empty' => '', 'data' => Dictionaries::getRegions()) + $htmlOptions); ?>
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
