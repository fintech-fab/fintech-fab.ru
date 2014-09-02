<?php
/**
 * @var SubscriptionWidget  $this
 * @var IkTbActiveForm      $oForm
 * @var ClientSubscribeForm $oModel
 */

$aClientPreProductList = Yii::app()->adminKreddyApi->getClientProductsList(false, true);
$aClientPostProductList = Yii::app()->adminKreddyApi->getClientProductsList(true, false);

$oModel->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

if ($oModel->product) {
	//устанавливаем в качестве выбранного пакета первый из массива доступных
	$oModel->setAttributesByProduct();
}
?>

<div class="alert">
	<ol>
		<li>Подключай сервис на 30 дней.</li>
		<li>Получай интересные предложения, как с умом потратить деньги для получения новых возможностей</li>
		<li>Реализуй свои возможности! Бери деньги тогда, когда они тебе действительно нужны. Возвращай всю сумму и бери
			деньги повторно
		</li>
	</ol>
</div>

<div class="well">
	<h4 style="display: inline">Сколько нужно?</h4> <a href="#" id="loan_amount_tip" onclick="return false;">[?]</a>
	<br /><br />
	<?= $oForm->radioButtonList($oModel, 'loan_amount', $oModel->getLoanAmounts(), array('class' => 'loan_amount_choose')) ?>
	<p id="loan_amount_tip_text" style="display: none;">
		Не всегда твои желания совпадают с нашими возможностями.<br /><br /> В некоторых случаях может быть одобрена
		меньшая сумма. </p>
</div>

<div class="well" id="product_type_choose_div">
	<h4 style="display: inline">Когда оплатишь абонентку?</h4>
	<a href="#" id="loan_amount_tip" onclick="return false;"></a> <br /><br />
	<?=
	$oForm->radioButtonList($oModel, 'product_type', $oModel::$aProductTypes, array(
		'class'        => 'product_type_choose',
		'template'     => '<label class="{labelCssClass}">{input}{label} <a href="#" id="product_type_{data_value}" onclick="return false;">[?]</a></label>',
		'labelOptions' => array(
			'style' => 'display: inline;'
		)
	)) ?>
	<p id="product_type_<?= $oModel::C_PRE_PAID ?>_tip_text" style="display: none;">
		750 руб. абонентки за подключение сервиса на месяц. За пользование деньгами - дополнительно 8 руб. в день.<br /><br />
		Максимальная стоимость сервиса в месяц - 990 руб. </p>

	<p id="product_type_<?= $oModel::C_POST_PAID ?>_tip_text" style="display: none;">
		Подключаешь сервис на месяц, берешь деньги, а абонентку платишь при первом возврате денег.<br /><br /> За
		пользование деньгами - дополнительно 15 руб. в день. Максимальная стоимость серивса в месяц - 1200 руб. </p>
</div>
<div id="agree_div">
	<div class="well">
		<h4>Последний этап:</h4>
		<?php
		echo $oForm->checkBoxRow($oModel, 'registry_agree', array('class' => 'agree_checkbox'));
		echo $oForm->checkBoxRow($oModel, 'not_public_agree', array('class' => 'agree_checkbox'));
		echo $oForm->checkBoxRow($oModel, 'my_interests_agree', array('class' => 'agree_checkbox'));
		echo $oForm->checkBoxRow($oModel, 'auto_debiting_agree', array('class' => 'agree_checkbox'));
		?>
	</div>

	<div class="form-actions" id="submit_div">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => $this->getSubscribeButtonLabel(),
		)); ?>
	</div>

</div>
<?php
Yii::app()->clientScript->registerScript('all_inclusive_product',
	'
	function visibility() {
		var loan_amount_checked = $(".loan_amount_choose:checked").length;
		if(loan_amount_checked) {
			$("#product_type_choose_div").show();
		} else {
			$("#product_type_choose_div").hide();
		}

		var product_type_checked = $(".product_type_choose:checked").length;
		if(product_type_checked) {
			$("#agree_div").show();
		} else {
			$("#agree_div").hide();
		}

		var agree_not_checked = $(".agree_checkbox:not(:checked)").length;
		if(agree_not_checked) {
			$("#submit_div").hide();
		} else {
			$("#submit_div").show();
		}
	}

	visibility();

	$(".loan_amount_choose, .product_type_choose, .agree_checkbox").change(function() {
		visibility();
	});


	var tips = [
		[$("#loan_amount_tip"), $("#loan_amount_tip_text").html()],
		[$("#product_type_' . $oModel::C_PRE_PAID . '"), $("#product_type_' . $oModel::C_PRE_PAID . '_tip_text").html()],
		[$("#product_type_' . $oModel::C_POST_PAID . '"), $("#product_type_' . $oModel::C_POST_PAID . '_tip_text").html()]
	];

	$.each(tips, function(link, tip) {
		tip[0].popover({
			"selector": "",
			"placement": "top",
			"content": tip[1],
			"html": "true"
		});
	});

	'
);

?>

