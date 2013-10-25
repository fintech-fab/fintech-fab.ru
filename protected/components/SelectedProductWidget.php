<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget {

	public $curStep = 1; // номер текущего шага
	public $chosenProduct; // индекс выбранного продукта

    public function run() {
	    //TODO выпилить при переходе на новую версию
		$this->chosenProduct=Yii::app()->clientForm->getSessionProduct();
		$this->render('selected_product');
    }
}
