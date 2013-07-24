<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->page_id,
);

/*$this->menu=array(
	array('label'=>'List Pages', 'url'=>array('index')),
	array('label'=>'Create Pages', 'url'=>array('create')),
	array('label'=>'Update Pages', 'url'=>array('update', 'id'=>$model->page_id)),
	array('label'=>'Delete Pages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->page_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Pages', 'url'=>array('admin')),
);*/
?>

<!--h1>View Pages #<?php //echo $model->page_id; ?></h1-->

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'page_id',
		'page_name',
		'page_title',
		'page_content',
	),
));*/ ?>

<?php
$this->pageTitle=Yii::app()->name." - ".CHtml::encode($model->page_title);
?>
<?php
$this->widget('TopPageWidget');

?>

<div class="container container_12">
	<div class="grid_12">

		<?php echo $model->page_content; ?>

	</div>
</div>