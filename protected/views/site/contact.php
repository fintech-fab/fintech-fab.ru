<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Отправка сообщения';


$this->showTopPageWidget = true;
?>
	<h2>Отправьте нам сообщение</h2>
	<div>
		<h5>Добрый день!</h5>

		<h5>Если у тебя возник вопрос, ты можешь отправить нам сообщение и мы обязательно ответим</h5>

	</div>
<?php
// функция для открытия окна JivoSite, delay(500) сделан т.к. без него не открывается почему-то, нужно сделать зедержку
Yii::app()->clientScript->registerScript('jivoSiteOpen', '
	function jivo_onLoadCallback() {
		$(document).delay(500).queue(function(){
			jivo_api.open();
		});
	}
', CClientScript::POS_HEAD);
?>