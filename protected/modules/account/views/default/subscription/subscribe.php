<?php
/* @var DefaultController $this */
/* @var ClientSubscribeForm $model */
/* @var IkTbActiveForm $form */

$this->pageTitle = Yii::app()->name . " - Подключение Пакета";
?>
	<h4>Подключение Пакета</h4>

	<div class="alert in alert-block alert-info">Обращаем Ваше внимание, что обработка запроса осуществляется ежедневно
		с 09:00 до 22:00 по московскому времени.
	</div>
<?php
$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'id'     => 'products-form',
	'action' => Yii::app()->createUrl('/account/selectChannel'),
));

$aClientProductList = Yii::app()->adminKreddyApi->getClientProductsList();

$model->product = Yii::app()->adminKreddyApi->getSubscribeSelectedProduct();

// если пакета в сессии нет
if ($model->product === false) {
	//устанавливаем в качестве выбранного пакета первый из массива доступных
	$model->product = reset(array_keys($aClientProductList));
}

echo $form->radioButtonList($model, 'product', $aClientProductList, array("class" => "all", 'uncheckValue' => $model->product));
echo $form->error($model, 'product');

?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'size'       => 'small',
			'label'      => 'Подключить Пакет',
		)); ?>
	</div>

<?php
$this->endWidget();
