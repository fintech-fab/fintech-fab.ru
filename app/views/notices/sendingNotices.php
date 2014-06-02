<div class="jumbotron">
	<h2>Отправка сообщений</h2>


</div>

<div class="col-md-12">

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#tab1" data-toggle="tab">Для кого</a>
	</li>
</ul>


<div class="tab-content">
	<div class="tab-pane active" id="tab1">

		<div class="row">
			<div class="pull-right col-xs-5">
				<img src="http://fintech-lab.com/images/information_items_128.jpeg" class="img-thumbnail">
			</div>

			<div class="col-xs-7">



			</div>
		</div>

	</div>

	<div class="tab-pane" id="tab2">

		<div class="row">

			<div class="pull-right col-xs-5">
				<img src="https://irs2.4sqi.net/img/general/width960/6648164_jV8TbAARapLx3x2hUtDtvK3UK5JSN7omQHhLhFHwxZg.jpg" class="img-thumbnail">
				<img src="/assets/classroom.01.jpg" class="img-thumbnail">
			</div>

			<div class="col-xs-7">



			</div>
		</div>

	</div>
	<div class="tab-pane" id="tab3">

		<div class="row">

			<div class="pull-right col-xs-5">
				<img src="https://irs0.4sqi.net/img/general/width960/6648164_7Amj1qKyTFSUqXnhCn2lXzCFt5IcIhjzNI3EQHtdoAQ.jpg" class="img-thumbnail">
			</div>

			<div class="col-xs-7">



			</div>
		</div>

	</div>
	<div class="tab-pane" id="tab4">


		<?=
		Form::open(array(
			'action' => 'vanguard',
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',
		)); ?>
		<div class="form-group">
			<label for="inputName" class="col-sm-3 control-label">Как звать-величать</label>

			<div class="col-sm-8">
				<?=
				Form::input('text', 'name', '', array(
					'placeholder' => 'Имя. Лучше с фамилией.',
					'class'       => 'form-control',
					'id'          => 'inputName',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputAbout" class="col-sm-3 control-label">О себе</label>

			<div class="col-sm-8">
				<?=
				Form::textarea('about', '', array(
					'placeholder' => 'Расскажите про себя в свободной форме, но обязательно на тему сотрудничества',
					'class'       => 'form-control',
					'id'          => 'inputAbout',
					'required'    => 'required',
					'rows'        => '10',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail" class="col-sm-3 control-label">Email для получения ответа</label>

			<div class="col-sm-8">

				<?=
				Form::input('email', 'email', '', array(
					'placeholder' => 'your@mail.com',
					'class'       => 'form-control',
					'id'          => 'inputEmail',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-10">
				<?=
				Form::button('Отправить', array(
					'type'  => 'submit',
					'class' => 'btn btn-default',
				));
				?>
			</div>
		</div>
		<?= Form::close(); ?>
	</div>
</div>

	<pre class="pull-right"><small><?= "&lt;?php echo " ?>"для ленивых - главный пейдж
			<a href="/">здесь</a>"<?= " ?>" ?></small></pre>

</div>