<?php
/**
 * @var $sFormName string
 */
?>
<div class="conditions">
	<strong>Ваши условия</strong>
	<ul>
		<li>- Размер Пакета -
			<span class="cost packet_size"><?= ""; //Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;руб.
		</li>
		<li class="break">- Период действия Пакета с даты подключения -
			<span class="cost price_month"><?= ""; //Dictionaries::$aDataPriceCounts[$this->chosenProduct] ?></span>
		</li>
		<li>- Размер первого займа -
			<span class="cost final_price"><?= ""; //Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;руб.
		</li>
		<li>- Стоимость подключения -
			<span class="cost price_count"><?= ""; //Dictionaries::$aDataPrices[$this->chosenProduct] ?></span>&nbsp;руб.
		</li>
		<li>- Сумма к возврату -
			<span class="cost final_price"><?= ""; //Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;руб.
		</li>
		<li class="break">- Дата возврата - <span class="cost date"><?= ""; //$getDateToPayUntil; ?></span>
			(<span class="cost week_day"><?= ""; //$getDateToPayUntil; ?></span>, до
			<span class="cost time">23:50</span>)
		</li>
		<li>- Канал получения - <span class="cost channel">на мобильный телефон (МТС, Билайн, Мегафон)</span></li>
	</ul>
</div>

<?php
$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/products.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('myConditions', '
		var products = jQuery("#' . $sFormName . '_product");
		var channels = jQuery("#' . $sFormName . '_channel_id");
		products.find(".radio").click(function () {
			showConditions(jQuery(this).find("label > span"), channels);
			jQuery(".conditions").show();
			jQuery("#conditions-img").show();
			;
		});

		channels.click(function(){
			showConditions(products.find("input:checked").parent().find("label > span"), channels);
		});

		products.find(".radio").each(function () {
			if ((jQuery(this).find("input:checked").attr("value")) !== undefined) {
				showConditions(jQuery(this).find("label > span"), channels);
			}
		});

		jQuery( function ($){
			//заставляем отработать скрипт обновления состояния для выбранного радиобаттона
			jQuery("#' . $sFormName . '_product .radio :checked").click();
		});

	', CClientScript::POS_READY);

?>
