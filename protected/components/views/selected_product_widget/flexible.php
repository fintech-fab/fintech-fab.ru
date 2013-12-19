<?php
/**
 * @var $sFormName string
 */
?>
<div class="alert alert-success conditions" style="margin-top: 20pt;">
	<h5 class="pay_legend">Выбранные условия</h5>
	<?php

	?>
	<ul>
		<li>- Размер займа:
			<span class="cost final_price"><?= ""; //Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
		</li>
		<li>- Канал получения: <span class="cost channel"></span>
		</li>
		<li>- Дата возврата займа: &nbsp;<span class="cost date"></span>
		</li>
		<li>- Необходимо вернуть:
			<span class="cost price_count"><?= ""; //Dictionaries::$aDataPrices[$this->chosenProduct] ?></span>&nbsp;рублей
		</li>
	</ul>
</div>

<?php
//эта функция предназначена для обработки нажатий на кнопки-переключатели, выбирающие канал
Yii::app()->clientScript->registerScript('radioButtonsTrigger', '
 var oChannelId = $("#' . $sFormName . '_channel_id");
 oChannelId.on("change",function(){

		var sChannel = $("#' . $sFormName . '").find("button[value*=" + this.value + "]").html();
		$(".cost.channel").html(sChannel);
		$("#amount").change();
		$("#time").change();
	});
oChannelId.change();
', CClientScript::POS_READY);
?>
