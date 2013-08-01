<?php

class ChosenConditionsWidget extends CWidget {

	public $curStep = 1; // номер текущего шага
	public $chosenProduct; // индекс выбранного продукта

    public function run() {
		$this->chosenProduct=Yii::app()->session['product'];
		$this->render('chosenconditions');
    }
}
