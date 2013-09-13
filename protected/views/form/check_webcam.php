<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="/static/css/main.css" />
	<link rel="stylesheet" type="text/css" href="/static/css/bootstrap-overload.css" />
	<link rel="stylesheet" href="<?= Yii::app()->request->baseUrl; ?>/static/css/style.css" type="text/css" />

	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
</head>
<body style="margin: 0px;">
<div style="width:400px; text-align: center">
	<video id="inputVideo" width="100%" autoplay loop style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"></video>
	<canvas id="inputCanvas" width="400" height="300" style="display: none; -webkit-transform: scaleX(-1); transform: scaleX(-1);"></canvas>
	<canvas id="overlay"></canvas>
</div>
<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/static/js/headtrackr.js"></script>

<script type="text/javascript">
	$(function () {
		var video = document.getElementById('inputVideo');
		var canvas = document.getElementById('inputCanvas');
		var canvasOverlay = document.getElementById('overlay');
		var contextOverlay = canvasOverlay.getContext('2d');
		var inRectangle = false;
		//запускаем трекер лица
		var headTracker = new headtrackr.Tracker({
			ui: false
		});
		headTracker.init(video, canvas);
		document.addEventListener('headtrackrStatus', function (event) {
			if (event.status == 'camera found') {

			}
		});
	});
</script>
</body>
</html>
