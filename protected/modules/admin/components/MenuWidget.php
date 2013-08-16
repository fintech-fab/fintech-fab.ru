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
					'name'  => 'index',
				),
				array(
					'label' => 'Добавить страницу',
					'name'  => 'create',
				),
				array(
					'label' => 'Управление страницами',
					'name'  => 'admin',
				),
				array(
					'label'   => 'Редактировать текущую страницу',
					'name'    => (!empty($oPagesModel)) ? 'update/' . $oPagesModel->page_id : null,
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
					'name'  => 'index',
				),
				array(
					'label' => 'Добавить вкладку',
					'name'  => 'create',
				),
				array(
					'label' => 'Управление вкладками',
					'name'  => 'admin',
				),
			),
			),
			array(
				'name' => 'footerLinks', 'label' => 'Нижние ссылки', 'content' => array(
				array(
					'label' => 'Список ссылок',
					'name'  => 'index',
				),
				array(
					'label' => 'Добавить ссылку',
					'name'  => 'create',
				),
				array(
					'label' => 'Управление ссылками',
					'name'  => 'admin',
				),
			),
			),
			array(
				'name' => 'site', 'label' => 'Сайт', 'content' => array(
				array(
					'label' => 'Выход',
					'name'  => 'logout',
				),
			)
			),
		);

		foreach ($this->aMenu as &$aTab) {
			$bIsActiveController = (Yii::app()->controller->id == $aTab['name']);
			$aTab['active'] = $bIsActiveController;

			// если не пуст контент
			if (!empty($aTab['content'])) {
				foreach ($aTab['content'] as &$aLink) {
					// генерируем ссылку по имени таба-родителя и собственно имени ссылки, если url не прописан явно
					$aLink['url'] = (empty($aLink['url']))
						? Yii::app()->createUrl('/admin/' . $aTab['name'] . '/' . $aLink['name'])
						: $aLink['url'];
					// ссылка активна, если активен таб-родитель и текущий экшн совпадает с именем ссылки
					$aLink['active'] = ($bIsActiveController && isset($aLink['name']) && Yii::app()->controller->action->id == $aLink['name']);
				}
			}
		}

		$this->render('menu');
	}
}

?>
