<?php

class StepsBreadCrumbs extends CWidget
{
	public $crumbs = array(
		array('Выбор суммы',array("/form")),
		array('Выбор способа получения',array("/form")),
		array('Личные данные',array("/form")),
		array('Адрес',array("/form")),
		array('Информация о работе',array("/form")),
		array('Отправка',array("/form")),
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
