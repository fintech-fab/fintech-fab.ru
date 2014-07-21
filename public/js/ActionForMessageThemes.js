$(document).ready( function(){
	$('[name="themes"]').click(function(event){
		$('#themeMessage').html("");
		var itm = $(event.target);
		$.post(
			"notices/theme/getMessage",
			{'themeId': itm.val()},
			function(data){
				if (typeof data == "string")
					$('#themeMessage').html(data);
			}
		);

	});
});

