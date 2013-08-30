<?php

class StepsBreadCrumbsWidget extends CWidget
{
	public $aCrumbs = array(
		// 2 параметр - номер шага для ссылки form/N и для нумерации, если есть 3 параметр - то он ставится в нумерации
		array('Выбор суммы', 1),
		array('Выбор способа получения', 2),
		array('Идентификация', 3),
		array('Личные данные', 6, 4),
		array('Адрес', 7, 5),
		array('Информация о работе', 8, 6),
		array('Отправка', 9, 7),
	);

	public $sDivider = '→';

	public $aHtmlOptions = array(
		'class' => 'breadcrumb',
		'id'    => 'steps',
	);

	public $iCurStep; // номер текущего шага

	public function run()
	{
		$this->iCurStep = Yii::app()->clientForm->getCurrentStep() + 1;
		$this->render('steps_bread_crumbs');
	}
}


