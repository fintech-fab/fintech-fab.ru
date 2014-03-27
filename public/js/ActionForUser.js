$(document).ready(function () {

	$('.wrap .descr').click(function () {
		event.preventDefault();
		$('#photo').hide();
		$('#drop-files').show();
	});
});
