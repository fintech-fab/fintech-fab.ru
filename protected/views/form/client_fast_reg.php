<?php
/* @var FormController $this */
/* @var ClientSelectProductForm $model */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

$this->pageTitle = Yii::app()->name;

$aCrumbs = Yii::app()->clientForm->getBreadCrumbs();

?>

<?php $this->widget('YaMetrikaGoalsWidget'); ?>
<div class="row">

	<?php $this->widget('StepsBreadCrumbsWidget', array('aCrumbs' => $aCrumbs)); ?>
	<?php
	if (Yii::app()->clientForm->hasError()) {
		?>
		<div class="alert alert-error"><?= Yii::app()->clientForm->getError(); ?></div>
	<?php } ?>

	<?php

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id'                   => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'clientOptions'        => array(
			'validateOnChange' => true,
			'validateOnSubmit' => true,
		),
		'action'               => Yii::app()->createUrl('/form/'),
	));

	$aProducts = Yii::app()->adminKreddyApi->getProducts();

	?>

	<ul id="tabs" class="nav nav-tabs">
		<?php foreach ($aProducts as $iKey => $aProduct) { ?>
			<li class="active">
				<a href="#product<?= $aProduct['id'] ?>" data-toggle="tab" style="font-size: 20pt;"><?= $aProduct['amount'] ?></a>
			</li>
		<?php } ?>

	</ul>

	<div class="span6">

		<div class="tab-content" id="myTabContent">
			<?php foreach ($aProducts as $iKey => $aProduct) { ?>
				<div id="product<?= $aProduct['id'] ?>" class="tab-pane fade <?= $iKey == array_keys($aProducts)[0] ? 'active in' : '' ?>">
					<p><?php

						//'name' => 'Кредди 3000'
						//'subscription_lifetime' => '2592000'
						//'loan_lifetime' => '604800'

						?>
						<strong><span class="packet_name"><?= $aProduct['name'] ?></span>"</strong>
					<ul>
						<li>Размер одного займа - <span class="cost final_price"><?= $aProduct['loan_amount'] ?></span>&nbsp;руб.
						</li>

						<li>Доступная сумма - <span class="cost packet_size"><?= $aProduct['amount'] ?></span>&nbsp;руб.
						</li>
						<li>Количество займов в пакете -
							<span class="cost count_subscribe"><?= $aProduct['loan_count'] ?></span>
						</li>

						<li>Стоимость подключения -
							<span class="cost price_count"><?= $aProduct['subscription_cost'] ?></span>&nbsp;руб.
						</li>
						<!--li>Период действия Пакета с даты подключения - <span class="cost price_month"></span>
						</li>

						<li>Сумма к возврату - <span class="cost final_price"></span>&nbsp;руб.
						</li>
						<li>Дата возврата - <span class="cost date"></span> (<span class="cost week_day"></span> до
							<span class="cost time">23:50</span>)
						</li-->
						<li>Канал получения -
							<span class="cost channel">на мобильный телефон (МТС, Билайн, Мегафон)</span></li>
					</ul>
					</p>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="span6">
		test
	</div>

	<div class="clearfix"></div>
	<div class="row span11">
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'submit',
				'type'       => 'primary',
				'label'      => SiteParams::C_BUTTON_LABEL_NEXT,
			)); ?>
		</div>
	</div>

	<?php
	$this->endWidget();
	?>

	<div class="clearfix"></div>
	<br />

	<div class="span8 offset2">
		<div class="alert in alert-block fade alert-info center">
			<strong>Если Вы уже являетесь нашим Клиентом, воспользуйтесь <?=
				CHtml::link('Личным кабинетом', Yii::app()
					->createUrl('account')) ?>.</strong>
		</div>
	</div>
</div>
<script lang="javascript">
	/*$('#tabs a').click(function (e) {
	 e.preventDefault();
	 $(this).tab('show');
	 })*/

</script>