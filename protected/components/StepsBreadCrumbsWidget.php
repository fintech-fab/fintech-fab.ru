<?php
/**
 * Class StepsBreadCrumbsWidget
 */
class StepsBreadCrumbsWidget extends CWidget
{
	public $aCrumbs = array(
		// 2 параметр - номер шага для ссылки form/N и для нумерации, если есть 3 параметр - то он ставится в нумерации
		array('Выбор суммы', 1),
		array('Выбор способа получения', 2),
		array('Личные данные', 3),
		array('Адрес', 4),
		array('Информация о работе', 5),
		array('Отправка', 6),
	);

	public $sDivider = '→';

	public $aHtmlOptions = array(
		'class' => 'breadcrumb',
		'id'    => 'steps',
	);

	public $iCurStep; // номер текущего шага

	public function run()
	{
		$this->iCurStep = Yii::app()->clientForm->getBreadCrumbsStep();
		$this->render('steps_bread_crumbs');
	}
}


