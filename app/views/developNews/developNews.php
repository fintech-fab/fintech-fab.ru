<?php
/**
 * @var integer $inTime
 */
?>
<div class="jumbotron">
	<h2>События разработки сайта</h2>

	
</div>

<div class="col-md-12">

	<?=
	Form::open(array(
		'action' => 'developNews',
		'class'  => 'form-horizontal',
		'role'   => 'form',
		'method' => 'get',
	)); ?>




	<div class="row">

			<div class="form-group">
				<label for="inTime" class="col-sm-3 control-label">Последние события за срок: </label>

				<div class="col-sm-2">
					<?=
					Form::select('inTime',
						array('1' => ' неделя',
                                 '2' => '2 недели',
                                 '3' => '3 недели',
                                 '4' => '4 недели'),
						"$inTime",
						array(	'class' => 'form-control', 'id' => 'inTime'	)
					);
					?>
					<?=
					Form::button('Обновить', array(
						'type'  => 'submit',
						'class' => 'btn btn-default',
					));
					?>
				</div>
			</div>




	</div>
	<?= Form::close(); ?>



	<ul class="nav nav-tabs">
	<li class="active">
		<a href="#tab1" data-toggle="tab">Общие события</a>
	</li>
	<li>
		<a href="#tab2" data-toggle="tab">Задачи</a>
	</li>

</ul>


<div class="tab-content">
	<div class="tab-pane active" id="tab1">

		<?=View::make('developNews.events', array('inTime' => $inTime)) ?>

	</div>

	<div class="tab-pane" id="tab2">

		<?=View::make('developNews.issuesEvents', array('inTime' => $inTime)) ?>

	</div>

</div>







</div>
