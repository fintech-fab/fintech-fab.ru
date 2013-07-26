<?php
/* @var $this TabsController */
/* @var $model Tabs */

$this->breadcrumbs=array(
	'Tabs'=>array('index'),
	$model->tab_id,
);

$this->menu=array(
	array('label'=>'Список страниц', 'url'=>array('pages/index')),
	array('label'=>'Создать страницу', 'url'=>array('pages/create')),
	array('label'=>'Управление страницами', 'url'=>array('pages/admin')),
	array('label'=>'Список вкладок', 'url'=>array('tabs/index')),
	array('label'=>'Создать вкладку', 'url'=>array('tabs/create')),
	array('label'=>'Управление вкладками', 'url'=>array('tabs/admin')),
	array('label'=>'Изменить вкладку', 'url'=>array('update', 'id'=>$model->tab_id)),
	array('label'=>'Удалить вкладку', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->tab_id),'confirm'=>'Вы уверены что хотите удалить эту вкладку?')),
);
?>

<h1>Отображение вкладки #<?php echo $model->tab_id; ?> "<?php echo $model->tab_name	; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'tab_id',
		'tab_name',
		'tab_title',
		'tab_content',
		'tab_order',
	),
)); ?>
