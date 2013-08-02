<?php
/* @var FormController $this*/
/* @var ClientSelectProductForm $model*/
/* @var IkTbActiveForm $form*/
/* @var ClientCreateFormAbstract $oClientCreateForm */

/*
 * Выбор суммы займа
 */

?>

<div class="row">

		<?php $this->widget('StepsBreadCrumbsWidget'); ?>

	<?php

	$this->pageTitle=Yii::app()->name;

	$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
		'id' => get_class($oClientCreateForm),
		'enableAjaxValidation' => true,
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnChange'=>true,
			'validateOnSubmit'=>true,
		),
		'action' => Yii::app()->createUrl('/form/'),
	));

	?>
	<div class="row span6">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/01T.png"/>
		<?php
		if(!($oClientCreateForm->product=Yii::app()->session['ClientSelectProductForm']['product']))
		{
			$oClientCreateForm->product = "1";
		}
		?>
		<?php echo $form->radioButtonListRow($oClientCreateForm, 'product', Dictionaries::$aProducts, array("class"=>"all"));?>

	</div>

	<?php $this->widget('ChosenConditionsWidget',array(
		'curStep'=>Yii::app()->clientForm->getCurrentStep()+1,
	)); ?>

	<div class="clearfix"></div>

	<div class="form-actions">
		<? $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => 'Далее →',
		)); ?>
	</div>
</div>
<script type="text/javascript">
	function BrowserCompatForVideo() {
		var
			oUpdateLinks = {
				msie    : 'http://www.microsoft.com/rus/windows/internet-explorer/worldwide-sites.aspx',
				mozilla : 'http://www.mozilla-europe.org/ru/firefox/',
				chrome  : 'http://www.google.ru/chrome?hl=ru',
				opera 	: 'http://www.opera.com/browser/'
			},
			sUpdateLink,

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

		this.getUpdateLink = function() {
			if( bMsie ) {
				return oUpdateLinks.msie;
			} else if ( bOpera ) {
				return oUpdateLinks.opera;
			} else if ( bMozilla ) {
				return oUpdateLinks.mozilla;
			} else {
				return oUpdateLinks.chrome;
			}
		};

		this.isRed = function() {
			return ( bMsie && iMajorVersion <= 7 ) // ... IE 6, IE 7
				||
				( // other
					!bMsie &&
						!bOpera &&
						!bMozilla &&
						!bWebkit
					)
				;
		};

		this.isYellow = function() {
			return !this.isRed()
				&&
				(
					( bMsie && iMajorVersion < 9 ) // IE 8
						||
						( bOpera && iMajorVersion < 11 )
						||
						( bMozilla && iMajorVersion < 4 )
						||
						( bWebkitChrome && iChromeVersion > 0 && iChromeVersion < 10 )
						||
						( bWebkit && iMajorVersion < 300 )
					)
				;
		};

		this.isGreen = function() {
			var bRed = this.isRed();
			var bYellow = this.isYellow();
			return !( bRed || bYellow );
		};

		this.getName = function() {
			var sName;
			if( bMsie ) {
				sName = 'Internet Explorer ' + iMajorVersion;
			} else if ( bOpera ) {
				sName = 'Opera ' + iMajorVersion;
			} else if ( bMozilla ) {
				sName = 'Firefox ' + iMajorVersion;
			} else if ( bWebkitChrome ) {
				sName = 'Chrome' + ( iChromeVersion ? ' ' + iChromeVersion : '' );
			} else if ( bWebkitSafari ) {
				sName = 'Safari';
			} else {
				sName = '';
			}
			return sName;
		};
	}


	$(function(){

		var oBrowserCompat = new BrowserCompatForVideo();
		var sBrowserName = oBrowserCompat.getName();
		alert(oBrowserCompat.getName());

		if( sBrowserName ) {
			$('.browser-engine' ).html( sBrowserName );
			if( oBrowserCompat.isGreen() ) {
				/*$( '.browser-kind' ).addClass( 'green' ).html( 'подходящий браузер' );
				$( '.browser-mdash' ).addClass( 'green' );
			} else {
				$( '.browser-kind' ).addClass( 'red' ).html( 'устаревший браузер' );
				$( '.browser-mdash' ).addClass( 'red' );*/
				alert("Всё ок!");
			}
		}
	});
</script>
<?

$this->endWidget();

?>
