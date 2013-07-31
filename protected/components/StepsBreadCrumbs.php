<?php

class StepsBreadCrumbs extends CWidget
{
	public $crumbs = array(
		array('Выбор суммы',array("/form/step1")),
		array('Выбор способа получения',array("/form/step2")),
		array('Личные данные',array("/form/step3")),
		array('Адрес',array("/form/step4")),
		array('Информация о работе',array("/form/step5")),
		array('Отправка',array("/form/step6")),
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
