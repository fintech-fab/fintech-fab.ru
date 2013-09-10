var leftTime;
function showUntilResend() {
	iSecondsLeft = Math.floor((leftTime - (new Date())) / 1000);
	if (iSecondsLeft < 0) {
		jQuery("#btnResend").removeAttr("disabled").removeClass("disabled");
		jQuery("#textUntilResend").hide();
		return;
	}
	jQuery("#textUntilResend").show();
	iMinutesLeft = Math.floor(iSecondsLeft / 60);
	iSecondsLeft -= iMinutesLeft * 60;
	if (iSecondsLeft < 10) {
		iSecondsLeft = "0" + iSecondsLeft;
	}
	jQuery("#untilResend").html(iMinutesLeft + ":" + iSecondsLeft);
	setTimeout(showUntilResend, 1000);
}
