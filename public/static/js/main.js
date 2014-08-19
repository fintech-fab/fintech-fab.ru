/**
 * глобальные настройки ajax-запросов и функций
 */

var errorHappened = false;

$(document).ready(function () {
	$(document).ajaxError(function (event, jqxhr, settings, exception) {
		if (!errorHappened && exception != '' && exception != 'abort') {
			bootbox.alert('Произошла ошибка! Перезагрузи страницу!', 'OK');
			//alertModal('Ошибка', 'Произошла ошибка! Перезагрузите страницу!', 'OK');
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
		$(document).delay(1000).queue(function () {
			errorHappened = false;
			$(document).clearQueue();
		});
	});

	confirmModal.modal('show');
};

function in_array(what, where) {
	for (var i = 0, length_array = where.length; i < length_array; i++)
		if (what == where[i])
			return true;
	return false;
}

function doOpenModalFrame(src, title) {
	var $modal = $("#modal-frame");
	$modal.modal({});
	$modal.find('iframe').attr('src', src);
	if (!title) {
		title = '';
	}
	$modal.find('.modal-title').html(title);
	$modal.modal('show');
	return false;
}
