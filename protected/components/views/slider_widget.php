<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
/* @var $aAmountValues */
/* @var $aTimeValues */
/* @var $aPercentage */
/* @var $aChannelCosts */
/* @var $bIsNeedNewClientAlert */
?>

	<h5 class="pay_legend">Выбери сумму перевода</h5>

<?= $form->dropDownListRow($model, 'amount', $aAmountValues, array('class' => 'hide', 'label' => false)); ?>


	<br />    <br />    <br />
	<h5 class="pay_legend">Выбери срок предоставления денег</h5>

<?= $form->dropDownListRow($model, 'time', $aTimeValues, array('class' => 'hide', 'label' => false)); ?>


<?php
Yii::app()->clientScript->registerScript('sliderWidgetVars', '
	var aPercentage = ' . CJSON::encode($aPercentage) . ';
	var aChannelCosts = ' . CJSON::encode($aChannelCosts) . ';

	function checkTime(i) {
		if (i < 10) {
			i = "0" + i;
		}
		return i;
	}

	function getDateToPayUntil(n) {
		var montharray = new Array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

		var d = new Date();
		d.setTime(d.getTime() + n * 24 * 60 * 60 * 1000);

		return d.getDate() + " " + montharray[d.getMonth()] + " " + checkTime(d.getFullYear());
	}

	', CClientScript::POS_BEGIN);
?>
<?php
//TODO сделать получение количества labels из API
//TODO сделать разбор номера канала из вида 8_9_10 в массив и поиск канала по нему (для iAddCost)

//показывать ли предупреждение, что сумма доступна только постоянным клиентам
//вшито в JS
if ($bIsNeedNewClientAlert) {
	$sNewClientAlert = 'if(iAmount >= 4000){
					$("button#submitNow").attr("disabled","disabled");
					$(".ui-slider #handle_amount").append(\'<span class="ui-slider-tooltip ui-widget-content ui-corner-all">Доступно постоянным клиентам.</span>\');
				} else {
					$("button#submitNow").attr("disabled",false);
					$(".ui-slider #handle_amount .ui-slider-tooltip").remove();
				}';
} else {
	$sNewClientAlert = '';
}

Yii::app()->clientScript->registerScript('sliderWidget', '
			var oChannelId = $("#' . get_class($model) . '_channel_id");
			var oAmount = $("#' . get_class($model) . '_amount");
			var oTime = $("#' . get_class($model) . '_time");
			oAmount.selectToUISlider({
				labels: 5,
				tooltip: false
			});

			oAmount.change(function () {
				var iAmount = parseInt(oAmount.attr("value"));

			' . $sNewClientAlert . '

				if(typeof aChannelCosts[iAmount] !== "undefined"){
					var iAddCost = aChannelCosts[iAmount][oChannelId.attr("value")];
				}

				if(typeof iAddCost === "undefined"){
					iAddCost=0;
				} else {
					iAddCost = parseInt(iAddCost);
				}
				$(".cost.final_price").html(iAmount);
				var aPercents = aPercentage[iAmount];
				var iPercent = parseInt(aPercents[oTime.attr("value")]);
				$(".cost.price_count").html(iAmount+iPercent+iAddCost);

			});
			oTime.change(function () {
				var iAmount = parseInt(oAmount.attr("value"));

				if(typeof aChannelCosts[iAmount] !== "undefined"){
					var iAddCost = aChannelCosts[iAmount][oChannelId.attr("value")];
				}
				if(typeof iAddCost === "undefined"){
					iAddCost=0;
				} else {
					iAddCost = parseInt(iAddCost);
				}
				$(".cost.date").html(getDateToPayUntil(oTime.attr("value")));
				var aPercents = aPercentage[iAmount];
				var iPercent = parseInt(aPercents[oTime.attr("value")]);
				$(".cost.price_count").html(iAmount+iPercent+iAddCost);

			});


			oTime.selectToUISlider({
				labels: 7,
				tooltip: false
			});

			oAmount.change();
			oTime.change();


	', CClientScript::POS_LOAD);
?>