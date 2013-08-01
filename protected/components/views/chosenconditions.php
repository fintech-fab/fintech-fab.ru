<div class="row span4 conditions" >
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/00T.png"/>
	<?php
	$n = Dictionaries::$aDataTimes[$this->chosenProduct];
	$d = new DateTime('now');
	$d->add(new DateInterval('P'.$n.'D'));
	$getDateToPayUntil = Dictionaries::$aDays[$d->format('w')].", ".$d->format('j')." ".Dictionaries::$aMonths[$d->format('n')]." ".$d->format('Y');
	?>
	<ul>
		<li>Сумма займа: <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[$this->chosenProduct]?></span> рублей</li>
		<li>Вернуть <span class="cost final_price"><?php echo Dictionaries::$aDataFinalPrices[$this->chosenProduct]?></span> рублей до: <span class="cost time">23:50</span>, <span class="cost date"><?php echo $getDateToPayUntil; ?></span></li>
		<li>Стоимость подписки: <span class="cost price_count"><?php echo Dictionaries::$aDataPrices[$this->chosenProduct]?></span> рублей</li>
		<li>Срок подписки: <span class="cost price_month"><?php echo Dictionaries::$aDataPriceCounts[$this->chosenProduct]?></span></li>
		<li>Количество займов по подписке: <span class="cost count_subscribe"><?php echo Dictionaries::$aDataCounts[$this->chosenProduct]?></span></li>
	</ul>
</div>

<div class="span2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/step<? echo ( $this->curStep > 6 )? '6' : $this->curStep; ?>.png"></div>

<?php
	if($this->curStep == 1)
	{
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('ext.myExt.assets').'/'
			).'/js/chosen-conditions.js'
		, CClientScript::POS_HEAD);
	}
?>
