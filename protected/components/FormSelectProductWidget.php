<?php

/**
 * Class FormSelectProductWidget
 */

class FormSelectProductWidget extends CWidget
{
	public $sSelectProductModelName = '';
	public $sSelectProductView = '';

	public function run()
	{
		$this->sSelectProductModelName = ClientFormComponent::getSelectProductModelName();
		$this->sSelectProductView = ClientFormComponent::getSelectProductView();

		if (empty($this->sSelectProductModelName) || empty($this->sSelectProductView)) {
			return;
		}

		$sModel = $this->sSelectProductModelName;

		$this->render('form_select_product_widget/' . $this->sSelectProductView, array(
				'oClientCreateForm'       => new $sModel(),
				'sSelectProductView'      => $this->sSelectProductView,
				'sSelectProductModelName' => $this->sSelectProductModelName,
			)
		);
	}
}
