<?php

/**
 * Class FormSelectProductWidget
 */

class FormSelectProductWidget extends CWidget
{
	public $sSelectProductModelName = 'ClientSelectProductForm';
	public $sSelectProductView = "main";

	public function run()
	{
		$sModel = $this->sSelectProductModelName;

		$this->render('form_select_product_widget/' . $this->sSelectProductView, array(
				'oClientCreateForm'       => new $sModel(),
				'sSelectProductView'      => $this->sSelectProductView,
				'sSelectProductModelName' => $this->sSelectProductModelName,
			)
		);
	}
}
