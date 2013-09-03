<?php
/**
 * @var $this DefaultController
 * @var $passForm
 * @var $passFormRender
 * @var $history
 */

$this->breadcrumbs = array(
	$this->module->id,
);
?>
<?php

echo '<pre>';
print_r($this->clientData);
echo '</pre>';
if ($this->clientData['code'] == 0) {
} else {
	echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
}
echo $passFormRender;

if ($this->smsState['smsAuthDone']) {
	echo '<pre>';
	print_r($history);
	echo '</pre>';
}
?>
