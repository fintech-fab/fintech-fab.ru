<?php

class StepsBreadCrumbs extends CWidget
{
	public $crumbs = array(
		array('Ввод телефона',array("site/join")),
		array('Личные данные',array("site/form1")),
		array('Личные данные 2',array("site/form2")),
		array('Видеоидентификация', array('site/identification')),
		array('Загрузка документов', array('site/documents')),
	);

	public $divider = ' → ';

	public $htmlOptions = array(
		'class' => 'breadcrumb',
		'id' => 'steps',
	);

	public $curStep = 1; // номер текущего шага

	public function run()
	{
		$this->render('stepsbreadcrumbs');
	}
}

?>