<?php

class ChosenConditionsWidget extends CWidget {

	public $curStep = 1; // номер текущего шага
	public $chosenProduct; // индекс выбранного продукта

    public function run() {
		$this->chosenProduct=(isset(Yii::app()->session['ClientSelectProductForm']['product']))?Yii::app()->session['ClientSelectProductForm']['product']:1;
		$this->render('chosen_conditions');
    }
}
