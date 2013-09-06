<?php
/**
 * @var $this DefaultController
 * @var $smsState
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);

$this->pageTitle = Yii::app()->name . ' - Личный кабинет - Состояние подписки';

$this->menu = array(
	array(
		'label'  => 'Состояние подписки', 'url' => array(
		Yii::app()->createUrl('account')
	),
		'active' => true,
	),
	array(
		'label' => 'История операций', 'url' => array(
		Yii::app()->createUrl('account/history')
	)
	)
);

if ($this->smsState['smsAuthDone']) {
	$this->menu[] = array(
		'label' => 'Тестовое действие', 'url' => array(
			Yii::app()->createUrl('account/test')
		)
	);
}

$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

$sBalance = $this->clientData['subscription']['balance'];
$sProduct = $this->clientData['subscription']['product'];
$sActivityTo = $this->clientData['subscription']['activity_to'];
$sAvailableLoans = $this->clientData['subscription']['available_loans'];
$sMoratoriumTo = $this->clientData['subscription']['moratorium_to'];

?>

	<h4>Состояние подписки</h4>

<?php
if (!$this->smsState['needSmsPass']) { //если не требуется авторизоваться по СМС-паролю
	if ($this->clientData['subscription'] == false) { //если нет подписки
		?>
		<h5>Нет активных подписок</h5>
	<?php
	} else {
		?>
		<strong>Баланс:</strong>  <?= $sBalance ?> руб. <br />
		<strong>Продукт:</strong> <?= $sProduct ?><br />
		<strong>Подписка активна до:</strong>  <?= $sActivityTo ?> <br />
		<strong>Доступно займов:</strong> <?= $sAvailableLoans ?><br />
		<?php
		if (!empty($sMoratoriumTo)) {
			?>
			<strong>Мораторий на получение займа до:</strong> <?= $sMoratoriumTo ?>
			<br />
		<?php
		}
	}

} else {
	?>
	<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>
<?php
}
?>
<?= $passFormRender // отображаем форму запроса СМС-пароля?>

<?php
echo '<pre>';
CVarDumper::dump($this->clientData);
echo '</pre>';