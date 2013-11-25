<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form IkTbActiveForm */
/* @var $iActiveTab int */

$this->pageTitle = Yii::app()->name . ' - Ваши вопросы - Наши ответы'; ?>

<h2>Ваши вопросы - Наши ответы</h2>

<?php
$this->widget(
	'bootstrap.widgets.TbTabs',
	array(
		'id'   => 'faq',
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(
			array(
				'label'   => 'Частые вопросы',
				'content' => 'Home Content',
				'active' => ($iActiveTab == 1),
			),
			array(
				'label'   => 'Задать вопрос',
				'content' => $this->renderPartial('contact_us', array('model' => $model), true),
				'active'  => ($iActiveTab == 2),
			),
		),
	)
);

?>
