<?php
/* @var DefaultController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */

?>
	<h5>Оформление подписки</h5>

<?php

$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
	'action' => Yii::app()->createUrl('/account/doSubscribe'),
));

//TODO убрать нафиг в модель
$aProducts = Yii::app()->adminKreddyApi->getProducts();


echo $form->radioButtonListRow($model, 'product', $aProducts, array("class" => "all"));

?>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'       => 'primary',
			'label'      => 'Далее →',
		)); ?>
	</div>

<?php

$this->endWidget();