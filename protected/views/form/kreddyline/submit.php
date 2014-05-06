<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type' => 'inline',
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
<div class="bx-wrapper" style="max-width: 100%;">
	<div class="bx-viewport hide" style="width: 100%; overflow: hidden; position: relative; height: 213px;">
		<ul class="bxslider" style="width: auto; position: relative;">
			<li>
				<!--Подключить-->
				<div class="hook-up">

					<span>Быстрая регистрация</span>

					<!--div class="row-input">
						<input class="w1 blured" type="text" value="Фамилия">
						<input class="w2 blured" type="text" value="Имя">
						<input class="w3 blured" type="text" value="Отчество">
					</div>
					<div class="row-input">
						<input class="w4 blured" type="text" value="Мобильный телефон">
						<input class="w5 blured" type="text" value="E-mail">
					</div>
					<p>
						<input type="checkbox" name="labeled" value="1" id="labeled_1" /> <label for="labeled_1"> Я
							подтверждаю достоверность введенных данных и<br />даю согласие на их обработку (подробная
							информация) </label>
					</p>
					<input type="submit" value="Подключить"-->
					<?= $form->errorSummary($oClientCreateForm); ?>
					<?= $form->textFieldRow($oClientCreateForm, 'last_name', array('style' => 'width: 105px;')); ?>
					&nbsp;

					<?= $form->textFieldRow($oClientCreateForm, 'first_name', array('style' => 'width: 105px;')); ?>
					&nbsp;

					<?= $form->textFieldRow($oClientCreateForm, 'third_name', array('style' => 'width: 105px;')); ?>
					<br /> <br />

					<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', array('style' => 'width: 170px;', 'size' => '15')); ?>
					&nbsp;

					<?= $form->textFieldRow($oClientCreateForm, 'email', array('style' => 'width: 170px;')); ?><br />


			<span class="confirm">
				<?php
				$oClientCreateForm->agree = false;
				echo $form->checkBox($oClientCreateForm, 'agree');
				echo $form->label($oClientCreateForm, 'agree', array('style' => 'width: 320px;'));
				?>
			</span>

					<div class="clearfix"></div>
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'buttonType'  => 'submit',
						'type'        => 'primary',
						'label'       => 'Зарегистрироваться',
						'htmlOptions' => array(
							'style' => 'width: 250px; margin-top: 10px;'
						),
					)); ?>
				</div>
				<!--/Подключить-->
				<div class="del-bx-next"></div>
			</li>
		</ul>
	</div>
</div>
<?php $this->endWidget(); ?>
<div id="bx-pager">
	<div class="del-tal-left-col"></div>
	<a class="del-left-but" data-slide-index="0" href=""><img class="act-corner act-corner-top" src="static/kreddyline/images/tab_corner_top.png"><img class="no-act" src="static/kreddyline/images/tab_icon1.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon1_act.png" alt=""><span><em>КРЕДДИтный<br />
				лимит</em></span></a>
	<a class="one-line" data-slide-index="1" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon2.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon2_act.png" alt=""><span><em>Условия
				оплаты</em></span></a>
	<a data-slide-index="2" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon3.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon3_act.png" alt=""><span><em>Куда
				перечислить<br /> деньги</em></span></a>
	<a class="one-line last active" data-slide-index="3" href=""><img class="act-corner act-corner-bot" src="static/kreddyline/images/tab_corner_bot.png"><img class="no-act" src="static/kreddyline/images/tab_icon4.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon4_act.png" alt=""><span><em>Подключить</em></span></a>
</div>
<script src="static/kreddyline/js/jquery.formstyler.min.js"></script>
<script lang="javascript">
	jQuery(".bx-viewport").fadeIn("slow");
</script>