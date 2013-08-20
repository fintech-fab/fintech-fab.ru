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
			jobInfoOk = false;
			$("#passportData").collapse("hide");
			var formName="' . get_class($oClientCreateForm) . '";
			var aAttrs = Array(
				"job_company",
				"job_position",
				"job_phone",
				"job_time",
				"job_monthly_income",
				"job_monthly_outcome",
				"have_past_credit"
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
				$("#sendHeading").attr("href","#sendForm");
				$("#sendForm").collapse("show");
				$("#sendForm").find(":input").prop("disabled",false);
				jobInfoOk = true;
			}
		}'
	)
);
?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'job_company', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'job_position', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'job_phone', array('class' => 'span3') + $htmlOptions) ?>
</div>
<div class="span5 offset1">
	<?= $form->dropDownListRow($oClientCreateForm, 'job_time', Dictionaries::$aJobTimes, array('class' => 'span2') + $htmlOptions); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'job_monthly_income', Dictionaries::$aMonthlyMoney, array('empty' => '', 'class' => 'span2') + $htmlOptions); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'job_monthly_outcome', Dictionaries::$aMonthlyMoney, array('empty' => '', 'class' => 'span2') + $htmlOptions); ?>
	<?= $form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo, $htmlOptions); ?>
</div>