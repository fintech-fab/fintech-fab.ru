function setIndex(idx) {
	if (idx < 0) {
		idx = 0;
	}
	$('.phone input')[idx].focus();
}

$(document).ready(function () {

	$(function () {
		$('select').selectbox();
	})

	$('.phone input').on('keyup keypress', function (e) {
		var letters = '1234567890';
		return (letters.indexOf(String.fromCharCode(e.which)) != -1);
	});
	$('.phone input').each(function (index, element) {
		$(this).on('keyup keypress keydown', function (e) {
			var that = this;
			setTimeout(function () {
				if (''.indexOf(String.fromCharCode(e.which)) != -1) {
					if ($(that).val().length == 0) {
						setIndex(index - 1);
					}
					return true;
				}
				if (index == 0 || index == 1) {
					if ($(that).val().length == 3) {
						$('.phone input')[index + 1].focus();
					}
					if ($(that).val().length + 1 > 3) {
						return false;
					}
				} else {
					if (index == 2) {
						if ($(that).val().length == 2) {
							$('.phone input')[index + 1].focus();
						}
						if ($(that).val().length + 1 > 2) {
							return false;
						}
					} else {
						if ($(that).val().length + 1 > 2) {
							return false;
						}
					}
				}
			}, 0);
		});
	});
	$("#form").submit(function () {
		$(".error").removeClass("error");
		$(".error-rule").hide();
		error = 0;
		var reg = new RegExp('^[-а-яё\\s]+$', 'i');
		if (!reg.test($(".line input[name=name]").val())) {
			$(".line input[name=name]").addClass("error");
			error = 1;
		}
		var reg = new RegExp('^[-а-яё\\s]+$', 'i');
		if (!reg.test($(".line input[name=lname]").val())) {
			$(".line input[name=lname]").addClass("error");
			error = 1;
		}
		var reg = new RegExp('^[-а-яё\\s]+$', 'i');
		if (!reg.test($(".line input[name=sname]").val())) {
			$(".line input[name=sname]").addClass("error");
			error = 1;
		}

		$('.phone input').each(function (index, element) {
			reg = new RegExp('^\\d{2,3}$', 'g');
			if (!reg.test($(this).val())) {
				$(this).addClass("error");
				error = 1;
			}
			if (index == 0) {
				reg = new RegExp('^9\\d{2}$', 'g');
				if (!reg.test($(this).val())) {
					$(this).addClass("error");
					error = 1;
				}
			}
		});

		if (!$(".line.rule input[name=rule]").prop("checked")) {
			$(".error-rule").show();
			error = 1;
		}
		reg = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/;
		if (!reg.test($("#email").val())) {
			$("#email").addClass("error");
			error = 1;
		}


		$(".line.date select").each(function (index, element) {
			reg = new RegExp('^\\d{1,4}$', 'g');
			if (!reg.test($(this).find("option:selected").attr("value"))) {
				$(".line.date span:eq(" + index + ")").find(".select").addClass("error");
				error = 1;
			}
		});

		$(".line.sex select").each(function (index, element) {
			reg = new RegExp('^[1-2]$', 'g');
			if (!reg.test($(this).find("option:selected").attr("value"))) {
				$(".line.sex span:eq(" + index + ")").find(".select").addClass("error");
				error = 1;
			}
		});

		if (error == 1) {
			return false;
		}

	});

	$("#email").focus(function () {

		$(".focused").removeClass("focused");

	});
	date = new Date();
	year = date.getFullYear() - 18;
	for (i = year; i >= 1900; i--) {
		$("#year").html($("#year").html() + "<option value='" + i + "'>" + i + "</option>");
	}
});