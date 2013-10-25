<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget {

	public $curStep = 1; // номер текущего шага
	public $chosenProduct; // индекс выбранного продукта

    public function run() {
		$this->chosenProduct=Yii::app()->clientForm->getSessionProduct();
		$this->render('selected_product');
    }
}
