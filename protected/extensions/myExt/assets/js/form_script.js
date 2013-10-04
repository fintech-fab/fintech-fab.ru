
jQuery(window).on('load',function() {
	$(".errorAlert").find(".alert-block").addClass("alert-block-fullform");

	oAddressRes = $("#address_res");
	oPersonalData = $("#personalData");
	oPassportData = $("#passportData");
	oAddress = $("#address");
	oJobInfo = $("#jobInfo");
	oSendForm = $("#sendForm");

	if (!$("#reg_as_res").find("input[type=checkbox]").prop("checked")) {

		oAddressRes.find(":input").attr("disabled", false).removeClass("disabled");
		oAddressRes.show();
		oAddressRes.find("label").append("<span class=\"required\">*</span>");
	} else {
		oAddressRes.find(":input").attr("disabled", "disabled").addClass("disabled").parents(".control-group").addClass("success").val("");
		oAddressRes.hide();
		oAddressRes.find("label").append("<span class=\"required\">*</span>");
	}


	oPassportData.find(":input").prop("disabled", true);
	oAddress.find(":input").prop("disabled", true);
	oJobInfo.find(":input").prop("disabled", true);
	oSendForm.find(":input").prop("disabled", true);

	/**
	 * вешаем на чекбокс обработчик, чтобы по смене сразу валидировать и менять состояние формы
	 */

	jQuery("#reg_as_res").find("input[type=checkbox]").change(function () {
		/*
		 * Проверяем, установлен или снят чекбокс, и либо убираем и дизейблим, либо наоборот соответствующие части формы
		 * Обязательно убираем класс error/success и очищаем поля при этом
		 */
		if (!$("#reg_as_res").find("input[type=checkbox]").prop("checked")) {
			oAddressRes.find(":input").attr("disabled", false).removeClass("disabled").parents(".control-group").removeClass("error success");
			oAddressRes.show();
		} else {
			oAddressRes.find(":input").attr("disabled", "disabled").addClass("disabled").val("").parents(".control-group").removeClass("error").addClass("success").find(".help-inline").hide();
			oAddressRes.hide();
		}


	});

	/**
	 * вешаем на радиобаттоны "Пол" обработчик, чтобы по смене сразу валидировать (почему-то в TbActiveForm нет
	 * валидации radioButtonListRow без подобных плясок с бубном)
	 */
	jQuery("#sex").find(":input").change(function () {
		var form = $("#" + formName);
		var settings = form.data("settings");
		var regExp = new RegExp(formName + "_sex");
		$.each(settings.attributes, function () {
			var sID = this.id;
			if (sID.match(regExp)) {
				this.status = 2; // force ajax validation
			}
		});
		form.data("settings", settings);

		// trigger ajax validation
		$.fn.yiiactiveform.validate(form, function (data) {
			$.each(settings.attributes, function () {
				if (this.id == formName + "_sex") {
					$.fn.yiiactiveform.updateInput(this, data, form);
					this.afterValidateAttribute();
				}
			});
		});
	});

	jQuery("#have_past_credit").find(":input").change(function () {
		var form = $("#" + formName);
		var settings = form.data("settings");
		var regExp = new RegExp("^" + formName + "_have_past_credit");
		$.each(settings.attributes, function () {
			var sID = this.id;
			if (sID.match(regExp)) {
				this.status = 2; // force ajax validation
			}
		});
		form.data("settings", settings);

		// trigger ajax validation
		$.fn.yiiactiveform.validate(form, function (data) {
			$.each(settings.attributes, function () {
				var sID = this.id;
				if (sID.match(regExp)) {
					$.fn.yiiactiveform.updateInput(this, data, form);
					this.afterValidateAttribute();
				}
			});
		});

	});

//по нажатии на "Паспортные данные" делаем force валидацию предыдущей части формы
	jQuery("#passportDataHeading").click(function () {
		if ($("#passportDataHeading").attr("href") == "#passportData") {
			return;
		}

		var form = $("#" + formName);
		var settings = form.data("settings");

		var aAttrs = new Array(
			formName + "_first_name",
			formName + "_last_name",
			formName + "_third_name",
			formName + "_birthday",
			formName + "_phone",
			formName + "_email",
			formName + "_complete",
			formName + "_sex",
			formName + "_sex_0",
			formName + "_sex_1"
		);

		$.each(settings.attributes, function () {
			if (in_array(this.id, aAttrs)) {
				this.status = 2; // force ajax validation
			}
		});
		form.data("settings", settings);

		// trigger ajax validation
		$.fn.yiiactiveform.validate(form, function (data) {
			$.each(settings.attributes, function () {
				if (in_array(this.id, aAttrs)) {
					$.fn.yiiactiveform.updateInput(this, data, form);
					if (this.id != formName + "_phone")
						this.afterValidateAttribute();
				}
			});
		});
	});

//по нажатии на "Постоянную регистрацию" делаем force валидацию предыдущей части формы
	jQuery("#addressHeading").click(function () {

		if (personalDataOk && $("#addressHeading").attr("href") != "#address") {
			var form = $("#" + formName);
			var settings = form.data("settings");

			var aAttrs = new Array(
				formName + "_passport_series",
				formName + "_passport_number",
				formName + "_passport_date",
				formName + "_passport_code",
				formName + "_passport_issued",
				formName + "_document",
				formName + "_document_number"
			);

			$.each(settings.attributes, function () {
				if (in_array(this.id, aAttrs)) {
					this.status = 2; // force ajax validation
				}
			});
			form.data("settings", settings);

			// trigger ajax validation
			$.fn.yiiactiveform.validate(form, function (data) {
				$.each(settings.attributes, function () {
					if (in_array(this.id, aAttrs)) {
						$.fn.yiiactiveform.updateInput(this, data, form);
						this.afterValidateAttribute();
					}
				});
			});
		}
	});

//по нажатии на "Место работы" делаем force валидацию предыдущей части формы
	jQuery("#jobInfoHeading").click(function () {

		if (personalDataOk && passportDataOk && $("#jobInfoHeading").attr("href") != "#jobInfo") {
			var form = $("#" + formName);
			var settings = form.data("settings");

			var aAttrs = new Array(
				formName + "_address_reg_region",
				formName + "_address_reg_city",
				formName + "_address_reg_address",
				formName + "_address_res_region",
				formName + "_address_res_city",
				formName + "_address_res_address",
				formName + "_relatives_one_fio",
				formName + "_relatives_one_phone",
				formName + "_friends_fio",
				formName + "_friends_phone"
			);

			$.each(settings.attributes, function () {
				if (in_array(this.id, aAttrs)) {
					this.status = 2; // force ajax validation
				}
			});
			form.data("settings", settings);

			// trigger ajax validation
			$.fn.yiiactiveform.validate(form, function (data) {
				$.each(settings.attributes, function () {
					if (in_array(this.id, aAttrs)) {
						$.fn.yiiactiveform.updateInput(this, data, form);
						this.afterValidateAttribute();
					}
				});
			});
		}
	});

//по нажатии на "Отправка" делаем force валидацию предыдущей части формы
	jQuery("#sendHeading").click(function () {

		if (personalDataOk && passportDataOk && addressOk && $("#sendHeading").attr("href") != "#sendForm") {
			var form = $("#" + formName);
			var settings = form.data("settings");

			var aAttrs = new Array(
				formName + "_job_company",
				formName + "_job_position",
				formName + "_job_phone",
				formName + "_job_time",
				formName + "_job_monthly_income",
				formName + "_job_monthly_outcome",
				formName + "_have_past_credit");

			$.each(settings.attributes, function () {
				if (in_array(this.id, aAttrs)) {
					this.status = 2; // force ajax validation
				}
			});
			form.data("settings", settings);

			// trigger ajax validation
			$.fn.yiiactiveform.validate(form, function (data) {
				$.each(settings.attributes, function () {
					if (in_array(this.id, aAttrs)) {
						$.fn.yiiactiveform.updateInput(this, data, form);
						this.afterValidateAttribute();
					}
				});
			});

		}
	});
});