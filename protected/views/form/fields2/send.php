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
			var formName="' . get_class($oClientCreateForm) . '";
			var aAttrs = Array(
				"numeric_code",
				"secret_question",
				"secret_answer",
				"product",
				"password",
				"password_repeat"
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
				$("#submitButton").removeClass("disabled");

				sendFormOk = true;
				yaCounter21390544.reachGoal("expand_5");
				}
		}'
	)
);
//отдельно задаем свойства для радиокнопок, для корректной отработки валидации и сопутствующих JS
$productHtmlOptions = array('errorOptions' => $htmlOptions['errorOptions'] + array('id' => get_class($oClientCreateForm) . '_product'), 'uncheckValue' => '999');

?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'numeric_code', array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->dropDownListRow($oClientCreateForm, 'secret_question', Dictionaries::$aSecretQuestions, array('class' => 'span3') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'secret_answer', array('class' => 'span3') + $htmlOptions); ?>
</div>
<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
<div class="span6" id="product">
	<?php
	$oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct();
	// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
	if (!isset($oClientCreateForm->product)) {
		$oClientCreateForm->product = reset(array_keys(FormatProductsChannels::getProducts()));
	}
	?>
	<div id="product">
		<?= $form->radioButtonListRow($oClientCreateForm, 'product', FormatProductsChannels::getProducts(), array("class" => "all") + $productHtmlOptions); ?>
	</div>
	<?= $form->passwordFieldRow($oClientCreateForm, 'password', $htmlOptions); ?>
	<?= $form->passwordFieldRow($oClientCreateForm, 'password_repeat', $htmlOptions); ?>
</div>
