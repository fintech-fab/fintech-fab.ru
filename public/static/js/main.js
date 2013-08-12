/**
 * глобальные настройки ajax-запросов
 */

/*
$(document).ready(function () {
	$.ajaxSetup({
		beforeSend: function () {
			//alertMessagesHandler.initLoading();
		},
		complete: function () {
			//alertMessagesHandler.initComplete();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			//alertMessagesHandler.initError(errorThrown);
		}
	});
});*/
/*
$(function() {
	$.ajaxSetup({
		error: function(jqXHR, exception) {
			if (jqXHR.status === 0) {
				alert('Not connect.\n Verify Network.');
			} else if (jqXHR.status == 404) {
				alert('Requested page not found. [404]');
			} else if (jqXHR.status == 500) {
				alert('Internal Server Error [500].');
			} else if (exception === 'parsererror') {
				alert('Requested JSON parse failed.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Ajax request aborted.');
			} else {
				alert('Uncaught Error.\n' + jqXHR.responseText);
			}
		}
	});
});*/