<script type="text/javascript">
	function BrowserCompatForVideo() {
		var
			rMobile = /(iphone|ipad|android|blackberry|phone)/i,
			oBrowser = $.browser,
			iMajorVersion = parseInt( $.browser.version.slice( 0, 3 ) ),
			iChromeVersion,
			bMsie = false,
			bMozilla = false,
			bOpera = false,
			bWebkit = false,
			bWebkitChrome = false,
			bWebkitSafari = false
			;

		if( oBrowser.msie ) {
			bMsie = true;
		} else if ( oBrowser.opera ) {
			bOpera = true;
		} else if ( oBrowser.mozilla ) {
			bMozilla = true;
		} else if ( oBrowser.webkit ) {
			bWebkit = true;
			bWebkitSafari = !window.chrome;
			bWebkitChrome = !bWebkitSafari;
			if( bWebkitChrome ) {
				var sUserAgent = navigator.userAgent.toLowerCase();
				sUserAgent = sUserAgent.substring( sUserAgent.indexOf( 'chrome/' ) + 7 );
				iChromeVersion = parseInt( sUserAgent.substring( 0, sUserAgent.indexOf('.') ) );
			}
		}

		this.isMobile = function() {
			return rMobile.test( navigator.userAgent );
		};

		this.getBrowser = function() {
			if( bMsie ) {
				return 'Internet Explorer';
			} else if ( bOpera ) {
				return 'Opera';
			} else if ( bWebkitSafari ) {
				return 'Safari';
			} else if ( bMozilla ) {
				return 'Firefox ' + iMajorVersion;
			} else if ( bWebkitChrome ) {
				return 'Chrome' + ( iChromeVersion ? ' ' + iChromeVersion : '' );
			} else {
				return null;
			}
		};

		this.isNotCompatible = function() {
			return (
					( this.isMobile() )
					||
					( bMsie )
					||
					( bOpera  )
					||
					( bWebkitSafari )
					||
					( bMozilla && iMajorVersion < 4 )
					||
					( bWebkitChrome && iChromeVersion > 0 && iChromeVersion < 25 )
				);
		}
	}

	$(function(){
		var oBrowserCompatForVideo = new BrowserCompatForVideo();

		if (oBrowserCompatForVideo.isNotCompatible()) {
			if(oBrowserCompatForVideo.getBrowser())
			{
				$("#your_browser").html(" Ваш браузер: <strong>"+oBrowserCompatForVideo.getBrowser()+"</strong>");
			}
			$("#browserFormat").slideDown('slow');
		}

		$("#browserFormat a.close").click(function(){
			$("#browserFormat").slideUp('slow');
		});
	});

</script>

<?php

Yii::app()->user->setFlash('error', '<strong>Внимание!</strong> После заполнения анкеты Вы можете пройти
видеоидентификацию, которая работает только в браузерах <strong>Chrome</strong> и <strong>Firefox</strong>
последних версий.<span id="your_browser"></span>');

$this->widget('bootstrap.widgets.TbAlert', array(
	'block'=>true, // display a larger alert block?
	'fade'=>false, // use transitions?
	'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
	'htmlOptions'=>array(
		'id'=>'browserFormat',
		'class'=>'hide',
	),
)); ?>


