<?php
/**
 * @var string $userMessage
 * @var string $title
 */
$userMessage = Session::get('userMessage');
$userMessageTitle = Session::get('title');
if (!$userMessage) {
	return;
}
?>

<div class="modal fade" id="userMessageModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title text-center"><?= HTML::entities($userMessageTitle) ?></h4>
			</div>
			<div class="modal-body">
				<p class="text-center"><?= HTML::entities($userMessage) ?></p>
			</div>
		</div>
	</div>
</div>

