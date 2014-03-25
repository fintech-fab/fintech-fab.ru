<?php
/**
 * @var ClientCreateFormAbstract $oClientCreateForm
 * @var IkTbActiveForm           $form
 * @var FastRegProductsWidget    $this
 */

?>
<div id="fastRegWidget">
	<?php $this->renderNavTabs() ?>
	<div class="span5">
		<div class="row">
			<?php $this->renderTabsContents(); ?>
		</div>
		<div class="row">

			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm),
				'enableAjaxValidation' => true,
				'type'                 => 'inline',
				'clientOptions'        => array(
					'validateOnChange' => true,
					'validateOnSubmit' => false,
				),
				'action'               => Yii::app()->createUrl('/form/'),
			));

			$oClientCreateForm->product = Yii::app()->clientForm->getSessionProduct();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->product)) {
				$oClientCreateForm->product = reset(array_keys(Yii::app()->productsChannels->getProducts()));
			}
			?>
			<div class="hide">
				<?= $form->radioButtonList($oClientCreateForm, 'product', Yii::app()->productsChannels->getProducts(), array("class" => "all")); ?>
				<br />
			</div>

			<?php
			$oClientCreateForm->channel_id = Yii::app()->clientForm->getSessionChannel();
			// если в сессии продукта нет, по умолчанию показываем первый продукт из массива доступных (ключ первого элемента)
			if (empty($oClientCreateForm->channel_id)) {
				$oClientCreateForm->channel_id = reset(array_keys(Yii::app()->productsChannels->getChannels()));
			}
			?>
			<h4>Куда перечислить деньги?</h4>

			<div class="channel-img"><label for="ClientFastRegForm_channel_id_0"><img src="/static/images/cards.png" /></label>
			</div>
			<div class="channel-img"><label for="ClientFastRegForm_channel_id_1"><img src="/static/images/mobile.png" /></label>
			</div>
			<?= $form->radioButtonList($oClientCreateForm, 'channel_id', Yii::app()->productsChannels->getChannels(), array("class" => "all")); ?>
			<div class="clearfix"></div>
			<?php /*$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'       => 'Подключить пакет',
				'htmlOptions' => array(
					'style' => 'width: 250px; margin-top: 10px;'
				),
			));*/
			?>
			<?php
			$this->endWidget();
			?>

		</div>
	</div>
	<div class="span6 offset1">
		<div class="row">
			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm) . '_fast',
				'enableAjaxValidation' => true,
				'type'                 => 'inline',
				'clientOptions'        => array(
					'validateOnChange' => true,
					'validateOnSubmit' => false,
				),
				'action'               => Yii::app()->createUrl('/form/'),
			));

			?>
			<h3>Быстрая регистрация</h3>
			<?= $form->errorSummary($oClientCreateForm); ?>
			<?= $form->textFieldRow($oClientCreateForm, 'last_name'); ?><br />

			<?= $form->textFieldRow($oClientCreateForm, 'first_name'); ?><br />

			<?= $form->textFieldRow($oClientCreateForm, 'third_name'); ?><br />

			<?= $form->phoneMaskedRow($oClientCreateForm, 'phone', array('size' => '15')); ?><br />

			<?= $form->textFieldRow($oClientCreateForm, 'email'); ?><br />
			<?php $oClientCreateForm->fast_reg = 1; ?>
			<?= $form->hiddenField($oClientCreateForm, 'fast_reg'); ?>
			<br /><br />
			<span class="confirm">
				<?php
				$oClientCreateForm->agree = false;
				echo $form->checkBoxRow($oClientCreateForm, 'agree');
				?>
			</span>

			<div class="clearfix"></div>
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'  => 'submit',
				'type'        => 'primary',
				'label'       => 'Зарегистрироваться',
				'htmlOptions' => array(
					'style' => 'width: 250px; margin-top: 10px;'
				),
			)); ?>
			<?php
			$this->endWidget();
			?>
		</div>
	</div>
</div>
<script lang="text/javascript">
	$('a[data-toggle="tab"]').on('shown', function (e) {
		var sTabDivId = $(e.target).attr('href');
		var sSelectedProductId = $(sTabDivId).attr('data-value');

		var sFormName = '#<?= get_class($oClientCreateForm) ?>';

		var oForm = $(sFormName);

		oInput = oForm.find('input[value=' + sSelectedProductId + ']');

		oInput.attr('checked', 'checked');

	})
</script>