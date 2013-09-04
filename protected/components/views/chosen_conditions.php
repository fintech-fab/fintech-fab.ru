<div class="row span4 conditions">
	<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/00T.png" />
	<?php
	$n = Dictionaries::$aDataTimes[$this->chosenProduct];
	$d = new DateTime('now');
	$d->add(new DateInterval('P' . $n . 'D'));
	$getDateToPayUntil = Dictionaries::$aDays[$d->format('w')] . ", " . $d->format('j') . " " . Dictionaries::$aMonths[$d->format('n')] . " " . $d->format('Y');
	?>
	<ul>
		<li>Сумма займа:
			<span class="cost final_price"><?= Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span> рублей
		</li>
		<li>Вернуть <span class="cost final_price"><?= Dictionaries::$aDataFinalPrices[$this->chosenProduct] ?></span>
			рублей до: <span class="cost time">23:50</span>, <span class="cost date"><?= $getDateToPayUntil; ?></span>
		</li>
		<li>Стоимость подписки:
			<span class="cost price_count"><?= Dictionaries::$aDataPrices[$this->chosenProduct] ?></span> рублей
		</li>
		<li>Срок подписки:
			<span class="cost price_month"><?= Dictionaries::$aDataPriceCounts[$this->chosenProduct] ?></span></li>
		<li>Количество займов по подписке:
			<span class="cost count_subscribe"><?= Dictionaries::$aDataCounts[$this->chosenProduct] ?></span></li>
	</ul>
</div>

<div class="span2" id="conditions-img">
	<img src="<?= Yii::app()->request->baseUrl ?>/static/img/step<?= ($this->curStep > 6) ? '6' : $this->curStep; ?>.png">
</div>

<?php
if ($this->curStep == 1) {
	$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/conditions.js';
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
	', CClientScript::POS_READY);
}
?>
