<div class="row span4 conditions">
	<strong>Выбранные условия:</strong>
	<ul>
		<li>Размер займа:
			<span class="cost final_price"><?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
		</li>
		<li>Количество займов в Пакете:
			<span class="cost count_subscribe"><?= "";//Dictionaries::$aDataCounts[$this->chosenProduct] ?></span></li>
		<li>Размер Пакета:
			<span class="cost packet_size"><?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
		</li>
		<li>Вернуть <span class="cost final_price">
				<?= "";//Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>&nbsp;рублей
			до:&nbsp;<span class="cost time">23:50</span>, <span class="cost date"><?= "";//$getDateToPayUntil; ?></span>
		</li>
		<li>Стоимость подключения:
			<span class="cost price_count"><?= "";//Dictionaries::$aDataPrices[$this->chosenProduct] ?></span>&nbsp;рублей
		</li>
		<li>Срок подключения:
			<span class="cost price_month"><?= "";//Dictionaries::$aDataPriceCounts[$this->chosenProduct] ?></span>
		</li>
		<li>Канал получения:&nbsp; <span class="cost">на мобильный телефон (МТС, Билайн, Мегафон)</span></li>
	</ul>
</div>

<div class="span2" id="conditions-img">
	<img src="<?= Yii::app()->request->baseUrl ?>/static/img/step<?= ($this->curStep > 6) ? '6' : $this->curStep; ?>.png">
</div>

<?php
if ($this->curStep == 1) {
	$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/products.js';
	Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);

	$sFormName = 'ClientSelectProductForm';
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
}
?>
