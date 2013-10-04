function beforeValidate(form){
	if(personalDataOk
		&&passportDataOk
		&&addressOk
		&&jobInfoOk
		||bFlagFullFormFilled
		){
		// разблокировали все поля, чтобы они провалидировались
		$("#jobInfo").find(":input").attr("disabled",false);
		$("#passportData").find(":input").attr("disabled",false);
		$("#address").find(":input").attr("disabled",false);
		$("#sendForm").find(":input").attr("disabled",false);

		return true;
	} else {
		bootbox.alert("Ошибка! Сначала следует заполнить все поля формы и подтвердить, что Вы согласны с условиями передачи и обработки персональных данных","OK");
		return false;
	}

}

function afterValidate(form){
	var hasError = false;
	oPersonalData = $("#personalData");
	oPassportData = $("#passportData");
	oAddress = $("#address");
	oJobInfo = $("#jobInfo");
	oSendForm = $("#sendForm");

	if(oPersonalData.find("div").hasClass("error")) {
		hasError = true;
		if(!oPersonalData.hasClass("in")){
			oPersonalData.collapse("show");
		}
	}

	if(oPassportData.find("div").hasClass("error")) {
		personalDataOk = true;
		hasError = true;
		$("#passportDataHeading").attr("href","#passportData");
		if(!oPassportData.hasClass("in")){
			oPassportData.collapse("show");
		}
	}
	if(oAddress.find("div").hasClass("error"))	{
		personalDataOk = true;
		passportDataOk = true;
		hasError = true;
		$("#passportDataHeading").attr("href","#passportData");
		$("#addressHeading").attr("href","#address");
		if(!oAddress.hasClass("in")){
			oAddress.collapse("show");
		}
	}
	if(oJobInfo.find("div").hasClass("error"))	{
		personalDataOk = true;
		passportDataOk = true;
		addressOk = true;
		hasError = true;
		$("#passportDataHeading").attr("href","#passportData");
		$("#addressHeading").attr("href","#address");
		$("#jobInfoHeading").attr("href","#jobInfo");
		if(!oJobInfo.hasClass("in")){
			oJobInfo.collapse("show");
		}
	}
	if(oSendForm.find("div").hasClass("error")) {
		personalDataOk = true;
		passportDataOk = true;
		addressOk = true;
		jobInfoOk = true;
		hasError = true;
		$("#passportDataHeading").attr("href","#passportData");
		$("#addressHeading").attr("href","#address");
		$("#jobInfoHeading").attr("href","#jobInfo");
		$("#sendFormHeading").attr("href","#sendForm");
		if(!oSendForm.hasClass("in")){
			oSendForm.collapse("show");
		}
	}

	if(hasError){
		$("#submitError").fadeIn(400).delay(4000).fadeOut( 800 );
	}

	return true;
}