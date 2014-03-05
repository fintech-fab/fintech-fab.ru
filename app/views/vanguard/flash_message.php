<?php
$userMessage = Session::get('userMessage');
if (!$userMessage) {
	return;
}
?>

<div class="modal fade" id="userMessageModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Заявка на Регистрацию принята.</h4>
			</div>
			<div class="modal-body">
				<p><?= $userMessage ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->


