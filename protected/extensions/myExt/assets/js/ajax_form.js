/**
 * предназначено для обновления тултипов и поповеров при загрузке страницы через ajax
 */
function updateAjaxForm() {

	var oBody = jQuery("body");

	jQuery("[data-toggle=popover]").popover();
	oBody.tooltip({"selector": "[rel=tooltip]"});
	oBody.off("click", "#submitButton");
	oBody.off("click", "#backButton");

}

function scrollAndFocus() {
	var oScrollAnchor = jQuery("#scrollAnchor");
	if (oScrollAnchor.length > 0) {
		jQuery('html, body').animate({
			scrollTop: oScrollAnchor.offset().top
		}, 500);
	}
	var oFormBody = jQuery("#formBody");
	var oErrorInput = oFormBody.find("input[type!='hidden'].error").first();
	if (oErrorInput.length > 0) {
		oErrorInput.delay(600).focus()
	} else {
		oFormBody.find("input[type!='hidden']").first().delay(600).focus();
	}
}