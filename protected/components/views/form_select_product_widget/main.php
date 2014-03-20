<?php
/**
 * @var $form                    IkTbActiveForm
 * @var $sSelectProductView      string
 * @var $sSelectProductModelName string
 * @var $oClientCreateForm       ClientSelectProductForm
 */
?>
<div id="form_selected_product">
	<div class="row">

		<div class="span6">
			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm),
				'enableAjaxValidation' => true,
				'bShowRequired' => false,
				'clientOptions'        => array(
					'validateOnChange' => true,
				),
				'action'               => Yii::app()->createUrl('/form/saveSelectedProduct'),
			));

			?>

			<div class="row">

				<?php
				$oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct();
				// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
				if (empty($oClientCreateForm->product)) {
					$oClientCreateForm->product = reset(array_keys(Yii::app()->productsChannels->getProducts()));
				}
				?>
				<?= $form->radioButtonListRow($oClientCreateForm, 'product', Yii::app()->productsChannels->getProducts(), array("class" => "all")); ?>
			</div>
			<br />

			<div class="row">
				<?php
				$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
				// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
				if (empty($oClientCreateForm->channel_id)) {
					$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannels()));
				}
				?>
				<?= $form->radioButtonListRow($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannels(), array("class" => "all")); ?>
			</div>


			<?php
			$this->endWidget();
			?>
		</div>

		<div class="span5" style="width:430px !important;">
		<?php $this->widget('SelectedProductWidget', array('sSelectProductView' => $sSelectProductView, 'sSelectProductModelName' => $sSelectProductModelName,)); ?>
		</div>
	</div>
</div>
