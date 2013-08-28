<?php
/**
 * @var $this DefaultController
 * @var $data
 */

$this->breadcrumbs = array(
	$this->module->id,
);
?>
	<h3 class="pay_legend">Личный кабинет</h3>
	<br />
	<h4><?= $data['last_name'] . ' ' . $data['first_name'] . ' ' . $data['third_name']; ?></h4>
	<p>Баланс счета:    <?= @$data['balance']; ?> рублей</p>
<?php

echo '<pre>';
print_r($data);
echo '</pre>';
