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
				$("#sendHeading").attr("href","#sendForm");
				if(!$("#sendForm").hasClass("in")){
					$("#sendForm").collapse("show");
				}
				if($("#address").hasClass("in")){
					$("#address").collapse("hide");
				}
				$("#sendForm").find(":input").prop("disabled",false);
				$("#submitButton").removeClass("disabled");

				jobInfoOk = true;
				yaCounter21390544.reachGoal("expand_4");
			}
		}'
	)
);
//отдельно задаем свойства для радиокнопок, для корректной отработки валидации и сопутствующих JS
$pastCreditHtmlOptions = array('errorOptions' => $htmlOptions['errorOptions']);
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
	<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
	<div id="have_past_credit">
		<?= $form->radioButtonListInlineRow($oClientCreateForm, 'have_past_credit', Dictionaries::$aYesNo, $pastCreditHtmlOptions); ?>
	</div>
</div>
