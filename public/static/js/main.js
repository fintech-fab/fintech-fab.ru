/**
 * глобальные настройки ajax-запросов
 */


$(document).ready(function () {
	$.ajaxSetup({
		beforeSend: function () {
			alertMessagesHandler.initLoading();
		},
		complete: function () {
			alertMessagesHandler.initComplete();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alertMessagesHandler.initError(errorThrown);
		}
	});
});