<div class="row span4 conditions" >
	<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/00T.png"/>
	<?php
	$n = Dictionaries::$aDataTimes[$this->chosenProduct];
	$d = new DateTime('now');
	$d->add(new DateInterval('P'.$n.'D'));
	$getDateToPayUntil = Dictionaries::$aDays[$d->format('w')].", ".$d->format('j')." ".Dictionaries::$aMonths[$d->format('n')]." ".$d->format('Y');
	?>
	<ul>
		<li>Сумма займа: <span class="cost final_price"><?= Dictionaries::$aDataFinalPrices[$this->chosenProduct]?></span> рублей</li>
		<li>Вернуть <span class="cost final_price"><?= Dictionaries::$aDataFinalPrices[$this->chosenProduct]?></span> рублей до: <span class="cost time">23:50</span>, <span class="cost date"><?= $getDateToPayUntil; ?></span></li>
		<li>Стоимость подписки: <span class="cost price_count"><?= Dictionaries::$aDataPrices[$this->chosenProduct]?></span> рублей</li>
		<li>Срок подписки: <span class="cost price_month"><?= Dictionaries::$aDataPriceCounts[$this->chosenProduct]?></span></li>
		<li>Количество займов по подписке: <span class="cost count_subscribe"><?= Dictionaries::$aDataCounts[$this->chosenProduct]?></span></li>
	</ul>
</div>

<div class="span2"><img src="<?= Yii::app()->request->baseUrl ?>/static/img/step<?= ( $this->curStep > 6 )? '6' : $this->curStep; ?>.png"></div>

<?php
if($this->curStep == 1){
	$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets').'/').'/js/chosen-conditions.js';
	Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
}
?>
