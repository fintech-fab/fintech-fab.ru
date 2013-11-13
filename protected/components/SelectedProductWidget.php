<?php

/**
 * Class SelectedProductWidget
 */

class SelectedProductWidget extends CWidget {

	public $curStep = 1; // номер текущего шага

    public function run() {
	    //TODO выпилить при переходе на новую версию
		$this->render('selected_product');
    }
}
