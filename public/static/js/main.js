/**
 * глобальные настройки ajax-запросов и функций
 */

var errorHappened = false;

$(document).ready(function () {
	$(document).ajaxError(function (event, jqxhr, settings, exception) {
		if (!errorHappened) {
			alertModal('Ошибка', 'Произошла ошибка! Перезагрузите страницу!', 'OK');
			errorHappened = true;
		}
	});
});


function alertModal(heading, question, okButtonTxt) {

	var confirmModal =
		$('<div class="modal hide fade modal-error">' +
			'<div class="modal-header">' +
			'<a class="close" data-dismiss="modal" >&times;</a>' +
			'<h3>' + heading + '</h3>' +
			'</div>' +

			'<div class="modal-body">' +
			'<p>' + question + '</p>' +
			'</div>' +

			'<div class="modal-footer">' +
			'<a href="#" id="okButton" class="btn btn-primary">' +
			okButtonTxt +
			'</a>' +
			'</div>' +
			'</div>');

	confirmModal.find('#okButton').click(function (event) {
		confirmModal.modal('hide');
		$(document).delay(200).queue(function(){errorHappened = false; $(document).clearQueue();});
	});

	confirmModal.modal('show');
};