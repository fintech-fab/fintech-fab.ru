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
				"sex",
				"complete"
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
				$("#passportDataHeading").attr("href","#passportData");
				if(!$("#passportData").hasClass("in")){
					$("#passportData").collapse("show");
				}
				$("#passportData").find(":input").prop("disabled",false);
				$("#addressHeading").removeClass("disabled cursor-default");

				personalDataOk = true;
				yaCounter21390544.reachGoal("expand_1");
			}
		}'
	)
);
//отдельно задаем свойства для радиокнопок, для корректной отработки валидации и сопутствующих JS
$sexHtmlOptions = array('errorOptions' => $htmlOptions['errorOptions'] + array('uncheckValue' => '999'));


Yii::app()->clientScript->registerScript('personalDataScript', '
$("#' . get_class($oClientCreateForm) . '_complete").parents(".controls").removeClass("controls");
', CClientScript::POS_READY);


?>
<div class="span5">
	<?= $form->textFieldRow($oClientCreateForm, 'last_name', SiteParams::getHintHtmlOptions('last_name') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'first_name', SiteParams::getHintHtmlOptions('first_name') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'third_name', SiteParams::getHintHtmlOptions('third_name') + $htmlOptions); ?>
	<?= $form->dateMaskedRow($oClientCreateForm, 'birthday', SiteParams::getHintHtmlOptions('birthday') + array('size' => '5', 'class' => 'inline') + $htmlOptions); ?>
</div>
<div class="span5 offset1">
	<?= $form->checkBoxRow($oClientCreateForm, 'complete', SiteParams::getHintHtmlOptions('complete') + $htmlOptions); ?>
	<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', SiteParams::getHintHtmlOptions('phone') + array('size' => '15') + $htmlOptions); ?>
	<?= $form->textFieldRow($oClientCreateForm, 'email', SiteParams::getHintHtmlOptions('email') + $htmlOptions); ?>
	<?php //отдельный DIV ID для радиокнопок, для обработки в JS ?>
	<div id="sex">
		<?= $form->radioButtonListRow($oClientCreateForm, 'sex', SiteParams::getHintHtmlOptions('sex') + Dictionaries::$aSexes, $sexHtmlOptions); ?>
	</div>
</div>
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
