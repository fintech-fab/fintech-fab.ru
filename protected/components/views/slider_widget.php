<?php
/* @var CWidget $this */
/* @var LoginForm $model */
/* @var IkTbActiveForm $form */
/* @var $aAmountValues */
/* @var $aTimeValues */
/* @var $aPercentage */
?>

	<h5 class="pay_legend">Выберите займ</h5>
<?= $form->dropDownList($model, 'amount', $aAmountValues, array('class' => 'hide', 'id' => 'amount')); ?>
	<br />    <br />    <br />    <h5 class="pay_legend">Выберите срок займа</h5>
<?= $form->dropDownList($model, 'time', $aTimeValues, array('class' => 'hide', 'id' => 'time')); ?>

<?php
Yii::app()->clientScript->registerScript('sliderWidgetVars', '
	var aPercentage = ' . CJSON::encode($aPercentage) . ';

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
Yii::app()->clientScript->registerScript('sliderWidget', '
			var oAmount = $("#amount");
			var oTime = $("#time");
			oAmount.selectToUISlider({
				labels: 7,
				tooltip: false
			});


			oAmount.change(function () {
				$(".cost.final_price").html(oAmount.attr("value"));
				var percents = aPercentage[oAmount.attr("value")];
				var percent = parseInt(percents[oTime.attr("value")]);
				$(".cost.price_count").html(parseInt(oAmount.attr("value"))+percent);

			});
			oTime.change(function () {
				$(".cost.date").html(getDateToPayUntil(oTime.attr("value")));
				var percents = aPercentage[oAmount.attr("value")];
				var percent = parseInt(percents[oTime.attr("value")]);
				$(".cost.price_count").html(parseInt(oAmount.attr("value"))+percent);
			});


			oTime.selectToUISlider({
				labels: 7,
				tooltip: false
			});

			oAmount.change();
			oTime.change();


	', CClientScript::POS_LOAD);
?>