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
			sendFormOk = false;
			if($("#personalData").hasClass("in")) $("#personalData").collapse("hide");
			if($("#passportData").hasClass("in")) $("#passportData").collapse("hide");
			if($("#address").hasClass("in")) $("#address").collapse("hide");
			if($("#jobInfo").hasClass("in")) $("#jobInfo").collapse("hide");
			var formName="' . get_class($oClientCreateForm) . '";
			var aAttrs = Array(
				"numeric_code",
				"secret_question",
				"secret_answer",
				"complete",
				"product"
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
				sendFormOk = true;
			}
		}'
	)
);
$productErrorOptions = array('errorOptions' => $htmlOptions['errorOptions'] + array('id' => get_class($oClientCreateForm) . '_product'), 'uncheckValue' => '999');
?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->checkBoxRow($oClientCreateForm, 'complete', $htmlOptions); ?>
</div>

<div class="span5" id="product">
	<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts2, array("class" => "all") + $productErrorOptions); ?>
</div>