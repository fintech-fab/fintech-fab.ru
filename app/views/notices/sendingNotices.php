<?php
/**
 * @var array $themes
 * @var array $users
 */
?>
<script src="/js/ActionForMessageThemes.js" type="text/javascript"></script>
<div class="jumbotron">
	<h2>Отправка сообщений</h2>

</div>

<div class="col-md-12">

<ul class="nav nav-tabs">
	<li class="active">
		<a href="#tab1" data-toggle="tab">Темы сообщений</a>
	</li>
	<li>
		<a href="#tab2" data-toggle="tab">Подписчики</a>
	</li>

</ul>

	<?=
	Form::open(array(
		'action' => 'notices.send',
		'class'  => 'form-horizontal',
		'role'   => 'form',
		'method' => 'post',
	)); ?>
<div class="tab-content">
		<div class="tab-pane active" id="tab1">

		<div class="row">
			<div class="pull-right col-xs-5">
				<div id="themeMessage" style="min-height: 100px"></div>
				<div class="form-group">
					<label for="comment" class="col-sm-3 control-label">Комментарий</label>

					<div class="col-sm-8">
						<?=
						Form::input('comment', 'comment', '', array(
							'placeholder' => 'Комментарий к сообщению',
							'class'       => 'form-control',
							'id'          => 'inputComment'
						));
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-10">
						<?=
						Form::button('Отправить', array(
							'type'  => 'submit',
							'name' => 'btnSelectedTheme',
							'class' => 'btn btn-default',
						));
						?>
					</div>
				</div>
			</div>


			<div class="col-xs-7">
				<table class="table table-striped">
					<?php foreach($themes as $theme): ?>
						<tr>
							<td>
								<label>
									<?=
									Form::radio("themes", "$theme->id");
									?>
									&nbsp;<?=e($theme->name)?>
								</label>
							</td>
							<td><?=$theme->comment;?></td>

						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>

	</div>

	<div class="tab-pane" id="tab2">

		<div class="row">
			<div class="col-xs-7">
				<?php foreach($users as $user): ?>

					<label>
						<?=	Form::checkbox("subscribers[]", "$user->id") ?>
						&nbsp;<?= trim(e($user->first_name)) . '  ' . e($user->last_name) ?>
					</label><br>

				<?php endforeach; ?>

			</div>


		</div>

	</div>

</div>
	<?= Form::close(); ?>



	<div class="jumbotron">
		<?=
		Form::open(array(
			'action' => 'notices.theme.add',
			'class'  => 'form-horizontal',
			'role'   => 'form',
			'method' => 'post',
		)); ?>

		<h3>Новая тема</h3>
		<div class="form-group">
			<label for="inputThemeName" class="col-sm-3 control-label">Название</label>

			<div class="col-sm-8">
				<?=
				Form::input('text', 'themeName', '', array(
					'placeholder' => 'Название темы.',
					'class'       => 'form-control',
					'id'          => 'inputThemeName',
					'required'    => 'required',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputTheme" class="col-sm-3 control-label">Сообщение</label>

			<div class="col-sm-8">
				<?=
				Form::textarea('themeText', '', array(
					'placeholder' => 'Текст сообщения',
					'class'       => 'form-control',
					'id'          => 'inputTheme',
					'required'    => 'required',
					'rows'        => '10',
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<label for="inputThemeComment" class="col-sm-3 control-label">Примечание</label>

			<div class="col-sm-8">

				<?=
				Form::input('text', 'themeComment', '', array(
					'placeholder' => 'Примечание к новой теме сообщений',
					'class'       => 'form-control',
					'id'          => 'inputThemeComment'
				));
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-10">
				<?=
				Form::button('Сохранить', array(
					'type'  => 'submit',
					'name' => 'btnNewTheme',
					'class' => 'btn btn-default',
				));
				?>
			</div>
		</div>
		<?= Form::close(); ?>
	</div>


</div>
