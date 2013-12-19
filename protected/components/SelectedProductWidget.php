<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget
{
	public $sSelectProductModelName = 'ClientSelectProductForm';
	public $sSelectProductView = "main";

	public function run()
	{
		$this->render('selected_product_widget/' . $this->sSelectProductView, array(
				'sFormName' => $this->sSelectProductModelName,
			)
		);
	}
}
