<?php
/**
 * @var $this MenuWidget
 */
?>

<?php
// генерируем контентную часть табов из части content массива меню
foreach ($this->aMenu as &$aTab) {
	if (!empty($aTab['content'])) {
		$aTab['content'] = $this->widget('bootstrap.widgets.TbMenu', array(
			'type'    => 'pills', // '', 'tabs', 'pills' (or 'list')
			'stacked' => false, // whether this is a stacked menu
			'items'   => $aTab['content'],
		), true);
	}
}
// собственно генерируем табы
$this->widget('bootstrap.widgets.TbTabs', array(
	'type' => 'tabs', // 'tabs' or 'pills'
	'tabs' => $this->aMenu,
	'id'   => 'tabsMenu',
));

?>
