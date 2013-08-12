function BrowserCompatForVideo() {
	var
		rMobile = /(iphone|ipad|android|blackberry|phone)/i,
		oBrowser = $.browser,
		iChromeVersion,
		iFirefoxVersion,
		bMsie = false,
		bMozilla = false,
		bOpera = false,
		bWebkitChrome = false,
		bWebkitSafari = false
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
		bWebkitSafari = !window.chrome;
		bWebkitChrome = !bWebkitSafari;
		if (bWebkitChrome) {

			if (sUserAgent.indexOf('opr/') !== -1) {
				bWebkitChrome = false;
				bOpera = !bWebkitChrome;
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
		} else if (bWebkitSafari) {
			return 'Safari';
		} else if (bMozilla) {
			return 'Firefox ' + ( iFirefoxVersion ? ' ' + iFirefoxVersion : '' );
		} else if (bWebkitChrome) {
			return 'Chrome' + ( iChromeVersion ? ' ' + iChromeVersion : '' );
		} else {
			return null;
		}
	};

	this.isNotCompatible = function () {
		return (
			( this.isMobile() )
				||
				( bMsie )
				||
				( bOpera  )
				||
				( bWebkitSafari )
				||
				( bMozilla && iFirefoxVersion <= 22 )
				||
				( bWebkitChrome && iChromeVersion > 0 && iChromeVersion <= 26 )
			);
	}
}

$(function () {
	var oBrowserCompatForVideo = new BrowserCompatForVideo();

	if (oBrowserCompatForVideo.isNotCompatible()) {
		if (oBrowserCompatForVideo.getBrowser()) {
			$("#your_browser").html(" Ваш браузер: <strong>" + oBrowserCompatForVideo.getBrowser() + "</strong>");
		}
		$("#browserFormat").show();
	}
});
