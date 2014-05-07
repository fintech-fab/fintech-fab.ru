<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 */

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'                   => get_class($oClientCreateForm),
	'enableAjaxValidation' => true,
	'type'                 => 'horizontal',
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
				<!--Условия оплаты-->
				<div class="terms-of-payment">

					<!--ol>
						<li>
							<img src="static/kreddyline/images/tab_text_icon1.png" alt="">
							<input type="radio" name="labeled" value="1" id="labeled_5" /> <label for="labeled_5">Оплатить
								сейчас<span>абонентская плата - 900 руб/мес</span></label>
						</li>
						<li>
							<img src="static/kreddyline/images/tab_text_icon2.png" alt="">
							<input type="radio" name="labeled" value="1" id="labeled_6" /> <label for="labeled_6">Оплатить
								потом<span>абонентская плата - 1000 руб/мес</span></label>
						</li>
					</ol-->
					<?php
					$aTypes = array(
						'3' => 'Оплатить сейчас',
						'4' => 'Оплатить потом',
					)
					?>
					<ol>
						<?=
						$form->radioButtonList($oClientCreateForm, 'type', $aTypes, array(

							'template' => '<li><img alt="" src="/static/kreddyline/images/tab_text_icon1.png"/><label class="{labelCssClass}">{input}{label}</label></li>'

						)); ?>
					</ol>
					<br />
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'id'          => 'backButton',
						'buttonType'  => 'ajaxButton',
						'ajaxOptions' => array(
							'update' => '#formBody',
						),
						'htmlOptions' => array(
							'class' => 'btn-left',
						),
						'url'         => Yii::app()
								->createUrl('/form/ajaxForm/' . Yii::app()->clientForm->getCurrentStep()),
						'label'       => SiteParams::C_BUTTON_LABEL_BACK,
					)); ?>
					<?php $this->widget('bootstrap.widgets.TbButton', array(
						'id'          => 'submitButton',
						'buttonType'  => 'ajaxSubmit',
						'htmlOptions' => array(
							'class' => 'btn-right',
						),
						'ajaxOptions' => array(
							'complete' => 'checkBlankResponse',
							'type'     => 'POST',

							'success'  => 'function(html){jQuery("#formBody").html(html);}',

						),
						'url'         => Yii::app()->createUrl('/form/ajaxForm'),
						'type'        => 'primary',
						'label'       => SiteParams::C_BUTTON_LABEL_NEXT,
					)); ?>
				</div>
				<!--/Условия оплаты-->
			</li>
		</ul>
	</div>
</div>
<?php $this->endWidget(); ?>
<div id="bx-pager">
	<div class="del-tal-left-col"></div>
	<a class="del-left-but" data-slide-index="0" href=""><img class="act-corner act-corner-top" src="static/kreddyline/images/tab_corner_top.png"><img class="no-act" src="static/kreddyline/images/tab_icon1.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon1_act.png" alt=""><span><em>КРЕДДИтный<br />
				лимит</em></span></a>
	<a class="one-line active" data-slide-index="1" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon2.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon2_act.png" alt=""><span><em>Условия
				оплаты</em></span></a>
	<a data-slide-index="2" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon3.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon3_act.png" alt=""><span><em>Куда
				перечислить<br /> деньги</em></span></a>
	<a class="one-line last" data-slide-index="3" href=""><img class="act-corner act-corner-bot" src="static/kreddyline/images/tab_corner_bot.png"><img class="no-act" src="static/kreddyline/images/tab_icon4.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon4_act.png" alt=""><span><em>Подключить</em></span></a>
</div>
<script src="static/kreddyline/js/jquery.formstyler.min.js"></script>
<script lang="javascript">
	jQuery(".bx-viewport").fadeIn("slow");
</script>