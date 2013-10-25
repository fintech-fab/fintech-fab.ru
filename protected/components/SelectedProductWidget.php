<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget {

	public $curStep = 1; // номер текущего шага
	public $chosenProduct; // индекс выбранного продукта

    public function run() {
	    //TODO выпилить при переходе на новую версию
		$this->chosenProduct=(isset(Yii::app()->session['ClientSelectProductForm']['product']))?Yii::app()->session['ClientSelectProductForm']['product']:1;
		$this->render('selected_product');
    }
}
