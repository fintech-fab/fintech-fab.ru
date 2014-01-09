/**
 * предназначено для обновления тултипов и поповеров при загрузке страницы через ajax
 */
function updateAjaxForm() {

	jQuery("[data-toggle=popover]").popover();
	jQuery("body").tooltip({"selector": "[rel=tooltip]"});
	jQuery("body").off("click", "#submitButton");
	jQuery("body").off("click", "#backButton");

}

function scrollAndFocus() {
	if ($("#scrollAnchor")) {
		$('html, body').animate({
			scrollTop: $("#scrollAnchor").offset().top
		}, 500);
	}
	$("#formBody").find("input[type!='hidden']").first().delay(600).focus();
}