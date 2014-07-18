<?php
use FintechFab\ActionsCalc\Models\Terminal;

/**
 * @var Terminal $terminal
 */

?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/ActionAccountCalc.js') ?>
</script>
<?= View::make('ff-actions-calc::account.inc.changeDataModal', array('terminal' => $terminal)) ?>
<div id="account">
	<h2 class="text-center">Профиль</h2><br>

	<div class="row">

		<div class="col-md-offset-4 col-md-4">
			<div class="row">
				<p class="col-md-6">ID пользователя:</p>

				<p class="col-md-6"><?= $terminal->id ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Имя пользователя:</p>

				<p class="col-md-5" id="username"><?= $terminal->name ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Url:</p>

				<p class="col-md-6" id="callback"><?= $terminal->url ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Queue:</p>

				<p class="col-md-5" id="queue"><?= $terminal->queue ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Key:</p>

				<p class="col-md-5" id="key"><?= $terminal->key ?></p>
			</div>

			<div class="col-md-offset-6 col-md-6">
				<?=
				Form::button('Изменить данные', array(
					'class'       => 'btn btn-info',
					'id'          => 'changeData',
					'data-toggle' => 'modal',
					'data-target' => '#changeDataModal',
				));
				?>
			</div>
		</div>
	</div>
</div>