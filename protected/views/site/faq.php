<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form IkTbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Ваши вопросы - Наши ответы';


$this->widget(
	'bootstrap.widgets.TbTabs',
	array(
		'id'   => 'faq',
		'type' => 'tabs', // 'tabs' or 'pills'
		'tabs' => array(
			array(
				'label'   => 'Частые вопросы',
				'content' => 'Home Content',
				'active'  => true
			),
			array('label' => 'Задать вопрос', 'content' => $this->renderPartial('contact_us', array('model' => $model), true)),
		),
	)
);

?>
