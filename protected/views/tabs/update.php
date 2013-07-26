<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	$model->tab_id=>array('view','id'=>$model->tab_id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список страниц', 'url'=>array('pages/index')),
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Управление страницами', 'url'=>array('pages/admin')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
	array('label'=>'Управление вкладками', 'url'=>array('tabs/admin')),
	array('label'=>'Список нижних ссылок', 'url'=>array('footerLinks/index')),
	array('label'=>'Создать нижнюю ссылку', 'url'=>array('footerLinks/create')),
	array('label'=>'Управление нижними ссылками', 'url'=>array('footerLinks/admin')),
);
?>

<h1>Изменение вкладки #<?php echo $model->tab_id; ?> "<?php echo $model->tab_name; ?>"</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>