<?php

/**
 * Class FastRegProductsWidget
 */
class FastRegProductsWidget extends CWidget
{

	public $oClientCreateForm;

	public function run()
	{

		$aProducts = Yii::app()->adminKreddyApi->getProducts();

		$this->render('fast_reg_products',
			array(
				'oClientCreateForm' => $this->oClientCreateForm,
				'aProducts'         => $aProducts
			)
		);
	}
}


