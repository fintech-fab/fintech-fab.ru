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

$aClientProductList = Yii::app()->adminKreddyApi->getClientProductsList();

$oModel->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

// если пакета в сессии нет
if ($oModel->product === false) {
	//устанавливаем в качестве выбранного пакета первый из массива доступных
	$oModel->product = reset(array_keys($aClientProductList));
}

echo $form->radioButtonList($oModel, 'product', $aClientProductList, array("class" => "all", 'uncheckValue' => $oModel->product));
echo $form->error($oModel, 'product');

?>

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
