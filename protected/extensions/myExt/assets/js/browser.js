function BrowserCompatForVideo() {
	var
		rMobile = /(iphone|ipad|android|blackberry|phone)/i,
		oBrowser = $.browser,
		iChromeVersion,
		iFirefoxVersion,
		bMsie = false,
		bMozilla = false,
		bOpera = false,
		bChrome = false,
		bSafari = false
		;

	var sUserAgent = navigator.userAgent.toLowerCase();

	if (oBrowser.msie) {
		bMsie = true;
	} else if (oBrowser.opera) {
		bOpera = true;
	} else if (oBrowser.mozilla) {
		bMozilla = true;

		sUserAgent = sUserAgent.substring(sUserAgent.indexOf('firefox/') + 8);
		iFirefoxVersion = parseInt(sUserAgent);

	} else if (oBrowser.webkit) {
		bSafari = !window.chrome;
		bChrome = !bSafari;
		if (bChrome) {

			if (sUserAgent.indexOf('opr/') !== -1) {
				bChrome = false;
				bOpera = !bChrome;
			}

			sUserAgent = sUserAgent.substring(sUserAgent.indexOf('chrome/') + 7);
			iChromeVersion = parseInt(sUserAgent);
		}
	}

	this.isMobile = function () {
		return rMobile.test(navigator.userAgent);
	};

	this.getBrowser = function () {
		if (bMsie) {
			return 'Internet Explorer';
		} else if (bOpera) {
			return 'Opera';
		} else if (bSafari) {
			return 'Safari';
		} else if (bMozilla) {
			return 'Firefox ' + ( iFirefoxVersion ? ' ' + iFirefoxVersion : '' );
		} else if (bChrome) {
			return 'Chrome' + ( iChromeVersion ? ' ' + iChromeVersion : '' );
		} else {
			return null;
		}
	};

	this.isNotCompatible = function () {
		return (
			( bMsie )
				||
				( bOpera  )
				||
				( bSafari )
				||
				( bMozilla && iFirefoxVersion <= 22 )
				||
				( bChrome && iChromeVersion > 0 && iChromeVersion <= 23 )
			);
	}
}

$(function () {
	var oBrowserCompatForVideo = new BrowserCompatForVideo();

	if (oBrowserCompatForVideo.isMobile()) {
		$("#attention_message").html(" <strong>Внимание!</strong> Во время заполнения анкеты Вы можете пройти видеоидентификацию. Видеоидентификация работает только <strong>на компьютере</strong>, в браузерах Chrome и Firefox последних версий.");
		if (oBrowserCompatForVideo.getBrowser()) {
			$("#your_browser").html(" Ваш браузер: <strong>" + oBrowserCompatForVideo.getBrowser() + "</strong> (мобильная версия). ");
		} else {
			$("#your_browser").html(" Вы зашли на сайт с мобильного устройства. ");
		}
		$("#your_browser").html($("#your_browser").html() + "Пожалуйста, зайдите на сайт с компьютера, если хотите пройти видеоидентификацию.");
		$("#get_browser").hide();
		$("#browserFormat").show();
	} else if (oBrowserCompatForVideo.isNotCompatible()) {
		if (oBrowserCompatForVideo.getBrowser()) {
			$("#your_browser").html(" Ваш браузер: <strong>" + oBrowserCompatForVideo.getBrowser() + "</strong>");
		}
		$("#browserFormat").show();
	}
});
