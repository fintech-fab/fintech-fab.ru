<?php
/**
 * @var $form                    IkTbActiveForm
 * @var $sSelectProductView      string
 * @var $sSelectProductModelName string
 * @var $oClientCreateForm       ClientFlexibleProductForm
 */

?>

<div id="form_selected_product">
	<div class="row">

		<div class="span6">
			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm),
				'enableAjaxValidation' => true,
				'clientOptions'        => array(
					'validateOnChange' => true,
				),
				'action'               => Yii::app()->createUrl('/form/saveSelectedProduct'),
			));


			$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
			// если в сессии канала нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->channel_id)) {
				$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannelsForButtons()));
			}

			$oClientCreateForm->amount = Yii::app()->clientForm->getSessionFlexibleProductAmount();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->amount)) {
				$oClientCreateForm->amount = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProduct()));
			}

			$oClientCreateForm->time = Yii::app()->clientForm->getSessionFlexibleProductTime();
			// если в сессии времени нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->time)) {
				$oClientCreateForm->time = reset(array_keys(Yii::app()->adminKreddyApi->getFlexibleProductTime()));
			}

			?>

			<div class="row span6">
				<?php $this->widget('SliderWidget', array('form' => $form, 'model' => $oClientCreateForm)); ?>
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
