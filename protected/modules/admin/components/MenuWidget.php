<?php

class MenuWidget extends CWidget
{
	public $aMenu = array();

	public function run()
	{
		$oPagesModel = null;
		$sUrl = null;

		// текущее действие - просмотр страницы?
		$bViewPage = (isset(Yii::app()->controller) && Yii::app()->controller->id == 'pages' && Yii::app()->controller->action->id == 'view');
		if ($bViewPage) {
			$oPagesModel = Pages::model()
				->findByAttributes(array('page_name' => Yii::app()->controller->actionParams['name']));
			$sUrl = Yii::app()->createAbsoluteUrl('/pages/view/' . $oPagesModel->page_name);
		}

		$this->aMenu = array(
			array(
				'name' => 'pages', 'label' => 'Страницы', 'content' => array(
				array(
					'label' => 'Список страниц',
					//'name'  => 'index',
					'url'   => array('/admin/pages/index')
				),
				array(
					'label' => 'Добавить страницу',
					//'name'  => 'create',
					'url'   => array('/admin/pages/create')
				),
				array(
					'label' => 'Управление страницами',
					'url'   => array('/admin/pages/admin'),
				),
				array(
					'label'   => 'Редактировать текущую страницу',
					'url'     => (!empty($oPagesModel)) ? array('/admin/pages/update/' . $oPagesModel->page_id) : null,
					'visible' => $bViewPage,
				),
				array(
					'label'       => 'Получить ссылку на страницу',
					'url'         => '#',
					'linkOptions' => array(
						'data-toggle' => 'modal',
						'data-target' => '#getLink',
						'onclick'     => "js: $('#link').val('$sUrl');"
					),
					'visible'     => $bViewPage,
				),
			),
			),
			array(
				'name' => 'tabs', 'label' => 'Вкладки', 'content' => array(
				array(
					'label' => 'Список вкладок',
					'url'   => array('/admin/tabs/index'),
				),
				array(
					'label' => 'Добавить вкладку',
					'url'   => array('/admin/tabs/create'),
				),
				array(
					'label' => 'Управление вкладками',
					'url'   => array('/admin/tabs/admin'),
				),
			),
			),
			array(
				'name' => 'footerLinks', 'label' => 'Нижние ссылки', 'content' => array(
				array(
					'label' => 'Список ссылок',
					'url'   => array('/admin/footerLinks/index'),
				),
				array(
					'label' => 'Добавить ссылку',
					'url'   => array('/admin/footerLinks/create'),
				),
				array(
					'label' => 'Управление ссылками',
					'url'   => array('/admin/footerLinks/admin'),
				),
			),
			),
			array(
				'name' => 'faqGroup', 'label' => 'FAQ: категории вопросов', 'content' => array(
				array(
					'label' => 'Список категорий',
					'url'   => array('/admin/faqGroup/index'),
				),
				array(
					'label' => 'Добавить категорию',
					'url'   => array('/admin/faqGroup/create'),
				),
				array(
					'label' => 'Управление категориями',
					'url'   => array('/admin/faqGroup/admin'),
				),
			),
			),
			array(
				'name' => 'faqQuestion', 'label' => 'FAQ: вопросы', 'content' => array(
				array(
					'label' => 'Список вопросов',
					'url'   => array('/admin/faqQuestion/index'),
				),
				array(
					'label' => 'Добавить вопрос',
					'url'   => array('/admin/faqQuestion/create'),
				),
				array(
					'label' => 'Управление вопросами',
					'url'   => array('/admin/faqQuestion/admin'),
				),
			),
			),
			array(
				'name' => 'files', 'label' => 'Изображения', 'content' => array(
				array(
					'label' => 'Управление изображениями',
					'url'   => array('/admin/files/imagesAdmin'),
				),
			),
			),
		);

		foreach ($this->aMenu as &$aTab) {
			$bIsActiveController = (Yii::app()->controller->id == $aTab['name']);
			$aTab['active'] = $bIsActiveController;
		}

		$this->render('menu');
	}
}


