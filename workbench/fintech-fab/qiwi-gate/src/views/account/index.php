<?php
use FintechFab\QiwiGate\Models\Merchant;

/**
 * @var Merchant $merchant
 */

?>
<script type="application/javascript">
	<?php require(__DIR__ . '/../layouts/inc/js/ActionAccountQiwi.js') ?>
</script>
<?= View::make('ff-qiwi-gate::account.inc.changeDataModal', array('merchant' => $merchant)) ?>
<div id="account">
	<h2 class="text-center">Профиль</h2><br>

	<div class="row">

		<div class="col-md-offset-4 col-md-4">
			<div class="row">
				<p class="col-md-6">ID пользователя:</p>

				<p class="col-md-6"><?= $merchant->id ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Callback:</p>

				<p class="col-md-6" id="callback"><?= $merchant->callback_url ?></p>
			</div>

			<div class="row">
				<p class="col-md-6">Имя пользователя:</p>

				<p class="col-md-5" id="username"><?= $merchant->username ?></p>
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