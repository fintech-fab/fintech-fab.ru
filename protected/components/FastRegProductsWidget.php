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
				'aProducts' => $this->aProducts
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
		<ul id="productsTabs" class="nav nav-tabs"><?php

		foreach ($this->aProducts as $aProduct) {
			?>
			<li <?= $aProduct['id'] == $this->getActiveProductId() ? 'class="active"' : '' ?>>
				<?php if ($aProduct['amount'] != 15000) { ?>
					<a href="#product_<?= $aProduct['id'] ?>" data-toggle="tab">
						<?= $aProduct['amount'] ?>
					</a>
				<?php } else { ?>
					<a href="#product_<?= $aProduct['id'] ?>" data-toggle="tab" style="width: 165px;">
						<?= ($aProduct['amount'] != 15000) ? $aProduct['amount'] : '<img src="/static/images/kreddy90.png"/>' ?>
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

			if ($aProduct['amount'] != 15000) {
				?>
				<div class="product_name">
					<img src="/static/images/kreddy_product.png" />&nbsp;<span><strong><?= $aProduct['amount'] ?></strong></span>
				</div>
			<?php } else { ?>
				<div class="product_name"><img src="/static/images/kreddy90.png" /></div>
			<?php } ?>

			Размер одного займа - <strong><?= $aProduct['loan_amount'] ?>&nbsp;руб.</strong><br />

			Доступная сумма - <strong><?= $aProduct['amount'] ?>&nbsp;руб.</strong><br />

			Количество займов в пакете - <strong><?= $aProduct['loan_count'] ?></strong><br />

			Возврат каждого займа - в течение <strong><?= $aProduct['loan_lifetime'] / (3600 * 24) ?>
				&nbsp;дней</strong> (с момента перечисления займа)<br />

			<strong>Стоимость подключения - <span class="red-font"><?= $aProduct['subscription_cost'] ?>
					&nbsp;руб.</span></strong><br />
		</div>
	<?php } ?>
		</div><?php
	}
}


