<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
/*$this->breadcrumbs=array(
	'Elecsnet',
);*/
?>
<?php
$this->widget('TopPageWidget');
?>

<div class="container container_12">
	<div class="grid_12">
		<h3>Вы уже являетесь нашим клиентом!</h3>
		<p>Если у вас есть вопросы - позвоните нам 8 (800) 555-75-78!</p>
		<br/>
		<?php
		$this->widget('BottomTabs',array(
			'tabs'=>Tabs::model()->findAll(array('order'=>'tab_order')),
			));
		?>

	</div>
</div>
