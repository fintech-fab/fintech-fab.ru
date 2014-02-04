<?php
/* @var DefaultController $this */

$this->pageTitle = Yii::app()->name . " - Идентификация на смартфоне";
?>
<h4>Идентификация на смартфоне</h4>

<div class="alert in alert-block alert-info span7">
	<div class="fright">
		<img width="180" height="180" src="/static/images/app_google_qr_180.png" />
	</div>

	<h4>Не работает веб-камера?</h4><br /> Пройдите идентификацию на своем смартфоне!<br /> Скачайте приложение прямо
	сейчас!<br />

	<br /><a href="https://play.google.com/store/apps/details?id=ru.kreddy" target="_blank">
		<img alt="Get it on Google Play" src="/static/images/ru_generic_rgb_wo_45.png" /> </a> <br /><br />Приложение
	доступно для смартфонов и планшетов с платформой Android (Samsung, HTC, Sony, Alcatel и другие).
	<?php if (!Yii::app()->adminKreddyApi->isFirstIdentification()): ?>
		<br /><br />После идентификации потребуется ввести данные документов, использованных при идентификации.
	<?php endif; ?>

	<br class="cl"/>

</div>
