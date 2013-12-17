<?php
/**
 * @var $this SessionExpireTimeWidget
 */
?>

<span id="sessionLeftTimeText">
		<?= $this->sLeftTimeMessage ?><span id="sessionLeftTime"></span>
	</span>
<script>
	var leftTime, iSecondsLeft, iMinutesLeft, iLiveTimeMinutes;

	function showUntilSessionEnd() {
		iSecondsLeft = Math.floor((leftTime - (new Date())) / 1000);
		if (iSecondsLeft < 0) {
			setTimeout(function () {
				jQuery("#sessionLeftTimeText").html('<?= $this->sExpiredMessage; ?>'); // задержка 10 сек
			}, 10000);
			window.location.reload(true); // релоад
			return;
		}
		iMinutesLeft = Math.floor(iSecondsLeft / 60);
		iSecondsLeft -= iMinutesLeft * 60;
		if (iSecondsLeft < 10) {
			iSecondsLeft = "0" + iSecondsLeft;
		}
		jQuery("#sessionLeftTime").html(iMinutesLeft + ":" + iSecondsLeft);
		setTimeout(showUntilSessionEnd, 1000);
	}

	iLiveTimeMinutes = <?= $this->iLiveTimeMinutes; ?>;
	leftTime = new Date();
	leftTime.setTime(leftTime.getTime() + iLiveTimeMinutes * 60 * 1000);
	showUntilSessionEnd();
</script>
