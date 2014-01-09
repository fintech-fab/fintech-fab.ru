<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";
?>
	<h4>Подключение Пакета</h4>

	<div class="alert in alert-block alert-info">Обращаем Ваше внимание, что обработка запроса осуществляется ежедневно
		с 10:00 до 22:00 по московскому времени.
	</div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/chooseChannel'),
));

$aClientProductsAndChannelsList = Yii::app()->adminKreddyApi->getClientProductsAndChannelsList();

// если есть доступные пакеты для данного пользователя
if (!empty($aClientProductsAndChannelsList)) {

	$this->widget('application.modules.account.components.ShowChannelsWidget', array('aAllChannels' => Yii::app()->adminKreddyApi->getProductsChannels(), 'aAvailableChannelKeys' => Yii::app()->adminKreddyApi->getClientChannels(),));

} else { // если доступных пакетов нет - выводим соответствующее сообщение
	?>

	<div class="alert alert-info"><?= Yii::app()->adminKreddyApi->getNoAvailableProductsMessage() ?></div>

<?php
}
$this->endWidget();
