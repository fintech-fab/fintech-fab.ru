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
		$this->sSelectProductModelName = ClientFormComponent::getSelectProductModelName();
		$this->sSelectProductView = ClientFormComponent::getSelectProductView();

		$this->render('selected_product_widget/' . $this->sSelectProductView, array(
				'sFormName' => $this->sSelectProductModelName,
			)
		);
	}
}
