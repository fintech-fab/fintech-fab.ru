<?php
/**
 * @var SubscriptionWidget  $this
 * @var IkTbActiveForm      $form
 * @var ClientSubscribeForm $oModel
 */

?>
	<h4><?= $this->getHeader(); ?></h4>

	<div class="alert in alert-block alert-info">Обращаем Ваше внимание, что обработка запроса осуществляется ежедневно
		с 09:00 до 22:00 по московскому времени.
	</div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/selectChannel'),
));

$aClientPreProductList = Yii::app()->adminKreddyApi->getClientProductsList(false, true);
$aClientPostProductList = Yii::app()->adminKreddyApi->getClientProductsList(true, false);

$oModel->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

// если пакета в сессии нет
if ($oModel->product === false) {
	//устанавливаем в качестве выбранного пакета первый из массива доступных
	$oModel->product = reset(array_keys($aClientPostProductList));
}?>

	<div class="product_selection">
		<h2>Предоплата</h2>
		<?php
		echo $form->radioButtonList($oModel, 'product', $aClientPreProductList, array(
			"class"        => "all",
			'uncheckValue' => $oModel->product,
			'baseID'       => get_class($oModel) . '_product_pre',
		));
		echo $form->error($oModel, 'product');
		?>
	</div>
	<div class="product_selection">
		<h2>Постоплата</h2>
		<?php
		echo $form->radioButtonList($oModel, 'product', $aClientPostProductList, array(
			"class"        => "all",
			'uncheckValue' => $oModel->product,
			'baseID'       => get_class($oModel) . '_product_post',
		));
		echo $form->error($oModel, 'product');
		?>
	</div>
	<div class="clearfix"></div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => $this->getSubscribeButtonLabel(),
		)); ?>
	</div>

<?php
$this->endWidget();
