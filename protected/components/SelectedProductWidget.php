<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget
{

	public $curStep = 1; // номер текущего шага

	public function run()
	{
		$this->render('selected_product');
	}
}
