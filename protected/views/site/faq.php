<?php
/* @var $this SiteController */
/* @var $form IkTbActiveForm */
/* @var $iActiveTab int */

$this->pageTitle = Yii::app()->name . ' - Твои вопросы - Наши ответы'; ?>

<h2>Твои вопросы - Наши ответы</h2>

<?php
$this->widget(
	'bootstrap.widgets.TbTabs',
	array(
		'id'   => 'faq',
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(
			array(
				'label'   => 'Частые вопросы',
				'content' => $sTableQuestions,
				'active'  => ($iActiveTab == 1),
			),
			array(
				'label'   => 'Задать вопрос',
				'content' => $sForm,
				'active'  => ($iActiveTab == 2),
			),
		),
	)
);

?>
