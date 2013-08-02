<?php

class StepsBreadCrumbsWidget extends CWidget
{
	public $crumbs = array(
		array('Выбор суммы',array("/form/1")),
		array('Выбор способа получения',array("/form/2")),
		array('Личные данные',array("/form/3")),
		array('Адрес',array("/form/4")),
		array('Информация о работе',array("/form/5")),
		array('Отправка',array("/form/6")),
	);
	
	public $divider = ' → ';

	public $htmlOptions = array(
		'class' => 'breadcrumb',
		'id' => 'steps',
	);

	public $curStep; // номер текущего шага

	public function run()
	{
		$this->curStep = Yii::app()->clientForm->getCurrentStep()+1;
		$this->render('steps_bread_crumbs');
	}
}

?>
