/**
 * предназначено для обновления тултипов и поповеров при загрузке страницы через ajax
 */
function updateAjaxForm() {
	jQuery("[data-toggle=popover]").popover();
	jQuery("body").tooltip({"selector": "[rel=tooltip]"});
	jQuery("body").off("click", "#submitButton");
}