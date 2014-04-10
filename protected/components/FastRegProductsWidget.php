<?php

/**
 * Class FastRegProductsWidget
 *
 */
class FastRegProductsWidget extends CWidget
{

	public $oClientCreateForm;
	private $aProducts;

	public function run()
	{

		$this->aProducts = Yii::app()->adminKreddyApi->getProducts();

		$this->render('fast_reg_products',
			array(
				'oClientCreateForm' => $this->oClientCreateForm,
				'aProducts'         => $this->aProducts
			)
		);
	}

	/**
	 * @return bool|int
	 */
	public function getActiveProductId()
	{
		$iProduct = Yii::app()->clientForm->getSessionProduct();
		if ($iProduct) {
			return $iProduct;
		}

		return array_keys($this->aProducts)[0];
	}

	public function renderNavTabs()
	{
		?>
		<ul id="productsTabs" class="nav nav-tabs">
		<li><a class="title"><img src="/static/images/kreddy-line.png" /></a></li>
		<?php

		foreach ($this->aProducts as $aProduct) {
			?>
			<li <?= $aProduct['id'] == $this->getActiveProductId() ? 'class="active"' : '' ?>>
				<?php if ($aProduct['name'] != 'Кредди 90 дней') { ?>
					<a href="#product_<?= $aProduct['id'] ?>" data-toggle="tab">
						<span class="limit-text">Лимит</span><br />
						<span class="loan-amount"><?= $aProduct['loan_amount'] ?></span> </a>
				<?php } else { ?>
					<a href="#product_<?= $aProduct['id'] ?>" data-toggle="tab" style="width: 165px;">
						<?= ($aProduct['name'] != 'Кредди 90 дней') ? $aProduct['loan_amount'] : '<img src="/static/images/kreddy90.png"/>' ?>
					</a>
				<?php } ?>
			</li>
		<?php
		}
		?></ul><?php
	}

	public function renderTabsContents()
	{
		?>

		<div class="tab-content" id="myTabContent">
		<?php foreach ($this->aProducts as $aProduct) { ?>
		<div data-value='<?= $aProduct['id'] ?>' id="product_<?= $aProduct['id'] ?>" class="tab-pane fade <?= $aProduct['id'] == $this->getActiveProductId() ? 'active in' : '' ?>">
			<?php

			//'name' => 'Кредди 3000'
			//'subscription_lifetime' => '2592000'
			//'loan_lifetime' => '604800'

			/*if ($aProduct['amount'] != 15000) {
				?>
				<div class="product_name">
					<img src="/static/images/kreddy_product.png" />&nbsp;<span><strong><?= $aProduct['loan_amount'] ?></strong></span>
				</div>
			<?php } else { ?>
				<div class="product_name"><img src="/static/images/kreddy90.png" /></div>
			<?php }*/
			?>
			<h4>Описание линии</h4>
			<ul class="product-info">
				<li>Срок действия линии - <strong><?= $aProduct['subscription_lifetime'] / 3600 / 24 ?>
						&nbsp;дней</strong></li>
				<li>Абонентская плата - <strong><?= $aProduct['subscription_cost'] ?>&nbsp;руб.</strong></li>
				<li>Лимит КРЕДДИтной линии - <strong><?= $aProduct['loan_amount'] ?>&nbsp;руб.</strong></li>

				<li>Размер каждого займа - <strong><?= $aProduct['loan_amount'] ?>&nbsp;руб.</strong></li>
				<li>Срок использования займа - <strong>от 1 до <?= $aProduct['subscription_lifetime'] / 3600 / 24 ?>
						&nbsp;дней</strong></li>
				<li>Количество займов - <strong>без ограничений</strong></li>

			</ul>
			<?php
			$sProductName = Dictionaries::createTransliteratedProductName($aProduct['name']);
			$sAboutProducturl = Yii::app()->createUrl('/pages/view/' . $sProductName);
			echo CHtml::link('Подробнее о пакете >', $sAboutProducturl);
			?>
		</div>
	<?php } ?>
		</div><?php
	}
}


