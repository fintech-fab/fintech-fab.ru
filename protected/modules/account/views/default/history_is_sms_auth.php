<?php
/**
 * @var $this DefaultController
 * @var $history
 * @var $historyProvider
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - История операций';

?>
<h4>История операций</h4>

<?php
$this->widget('application.modules.account.components.HistoryWidget', array(
	'dataProvider' => $historyProvider,
));
?>
