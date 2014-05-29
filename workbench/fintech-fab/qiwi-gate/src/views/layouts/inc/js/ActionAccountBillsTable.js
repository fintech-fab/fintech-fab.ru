$(document).ready(function () {

	$('.refund-button').click(function () {
		var bill_id = this.id.split('_');
		var tableRefund = $('#table-refund-' + bill_id[1] + ' > .container');
		if (tableRefund.html() == '') {
			$.ajax({
				type: "GET",
				url: 'billsTable/getRefund/' + bill_id[1],
				success: function (data) {
					tableRefund.append(data);
				}
			});
		}
		$('#table-refund-' + bill_id[1]).toggle();
	});
});
