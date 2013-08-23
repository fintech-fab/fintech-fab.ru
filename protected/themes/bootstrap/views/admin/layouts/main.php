<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php
$oPagesModel = null;
$sUrl = null;

// текущее действие - просмотр страницы?
$bViewPage = (isset(Yii::app()->controller) && Yii::app()->controller->id == 'pages' && Yii::app()->controller->action->id == 'view');
if ($bViewPage) {
	$oPagesModel = Pages::model()
		->findByAttributes(array('page_name' => Yii::app()->controller->actionParams['name']));
	$sUrl = Yii::app()->createAbsoluteUrl('/pages/view/' . $oPagesModel->page_name);
}

$aMenu = array(
	array(
		'class' => 'bootstrap.widgets.TbMenu',
		'items' => array(
			array(
				'name'  => 'pages',
				'label' => 'Страницы',
				'items' => array(
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
				'name'  => 'tabs',
				'label' => 'Вкладки',
				'items' => array(
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
				'name'  => 'footerLinks',
				'label' => 'Нижние ссылки',
				'items' => array(
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
		),
	),
	(!Yii::app()->user->isGuest)?'<a href="'.Yii::app()->createUrl("/admin/logout").'" class="btn btn-danger pull-right"><i class="icon-white icon-remove"></i> Выйти из системы</a>':'',
);

$this->widget('bootstrap.widgets.TbNavbar', array(
	'type'     => 'inverse', // null or 'inverse'
	'brand'    => 'Кредди - Панель администрирования',
	'brandUrl' => '#',
	'items'    => $aMenu,
));


?>


<div class="container" id="page">

	<?php if (isset($this->breadcrumbs)): ?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif ?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br /> All Rights Reserved.<br />
		<?php echo Yii::powered(); ?>
	</div>
	<!-- footer -->

</div>
<!-- page -->

</body>
</html>
