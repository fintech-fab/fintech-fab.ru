$(document).ready(function () {

	$('#photo').on('click', '.wrap .descr', function () {
		event.preventDefault();
		$('#photo').hide();
		$('.photo_upload').show();
	});
	$('#clear-btn').click(function () {
		$('#photo').show();
		$('.photo_upload').hide();
	});
});
