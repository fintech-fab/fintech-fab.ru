<div class="row span4 conditions">
	<strong>Выбранные условия:</strong>
	<?php
	/*$n = Dictionaries::$aDataTimes[$this->chosenProduct];
	$d = new DateTime('now');
	$d->add(new DateInterval('P' . $n . 'D'));
	$getDateToPayUntil = Dictionaries::$aDays[$d->format('w')] . ", " . $d->format('j') . " " . Dictionaries::$aMonths[$d->format('n')] . " " . $d->format('Y');
	*/

	//TODO сделать формирование данных для виджета на основе данных из API
	?>
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
		<li>Канал получения:&nbsp;
			<span class="cost">на мобильный телефон</span></li>
	</ul>
</div>

<div class="span2" id="conditions-img">
	<img src="<?= Yii::app()->request->baseUrl ?>/static/img/step<?= ($this->curStep > 6) ? '6' : $this->curStep; ?>.png">
</div>

<?php
if ($this->curStep == 1) {
	$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/products.js';
	Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);

	$sFormName = SiteParams::B_FULL_FORM ? 'ClientSelectProductForm2' : 'ClientSelectProductForm';
	Yii::app()->clientScript->registerScript('myConditions', '
		jQuery("#' . $sFormName . ' .radio").click(function () {
			showConditions(jQuery(this).find("label > span"));
			jQuery(".conditions").show();
			jQuery("#conditions-img").show();
			;
			if (jQuery(this).find("input:checked").attr("value") == 0) {
				jQuery(".conditions").hide();
				jQuery("#conditions-img").hide();
				;
			}
		});

		jQuery("#' . $sFormName . ' .radio").each(function () {
			if ((jQuery(this).find("input:checked").attr("value")) !== undefined) {
				showConditions(jQuery(this).find("label > span"));
				if (jQuery(this).find("input:checked").attr("value") == 0) {
					jQuery(".conditions").hide();
					jQuery("#conditions-img").hide();
					;
				}
			}
		});

		showConditions(jQuery("#' . $sFormName . ' .radio:first label > span"));

		jQuery( function ($){
			//заставляем отработать скрипт обновления состояния для выбранного радиобаттона
			jQuery("#' . $sFormName . ' .radio :checked").click();
		});

	', CClientScript::POS_READY);
}
?>
