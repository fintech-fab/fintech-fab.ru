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
			personalDataOk = false;
			var formName="' . get_class($oClientCreateForm) . '";
			var aAttrs = Array(
				"first_name",
				"last_name",
				"third_name",
				"birthday",
				"phone",
				"email",
				"sex"
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
				$("#passportDataHeading").attr("href","#passportData");
				$("#passportData").collapse("show");
				$("#passportData").find(":input").prop("disabled",false);
				personalDataOk = true;
			}
		}'
	)
);
//отдельно задаем свойства для радиокнопок, для корректной отработки валидации и сопутствующих JS
$sexHtmlOptions = array('errorOptions' => $htmlOptions['errorOptions'] + array('id' => get_class($oClientCreateForm) . '_sex'), 'uncheckValue' => '999');
?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'last_name', $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'first_name', $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'third_name', $htmlOptions); ?>
	<?= $form->dateMaskedRow($oClientCreateForm, 'birthday', array('size' => '5', 'class' => 'inline') + $htmlOptions); ?>
</div>
<div class="span5 offset1">
	<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', array('size' => '15') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'email', $htmlOptions); ?>
	<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
	<div id="sex">
		<?= $form->radioButtonListRow($oClientCreateForm, 'sex', Dictionaries::$aSexes, $sexHtmlOptions); ?>
	</div>
</div>
