<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-overload.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/style.css" type="text/css" />

	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
</head>
<body>
<div style="width:400px">
	<div id="instructions">Разрешите использование веб-камеры, если она есть, чтобы пройти видеоидентификацию</div>
	<canvas id="inputCanvas" width="400" height="300" style="display: none; -webkit-transform: scaleX(-1); transform: scaleX(-1);"></canvas>
	<video id="inputVideo" width="100%" autoplay loop style="height: 320px; -webkit-transform: scaleX(-1); transform: scaleX(-1);"></video>
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
		headTracker.start();

		document.addEventListener('headtrackrStatus', function (event) {
			if (event.status == 'camera found') {
				//рисуем квадрат
				canvasOverlay.style.position = 'absolute';
				canvasOverlay.style.top = parseInt($(inputVideo).position().top + 20) + 'px';
				canvasOverlay.style.left = parseInt($(inputVideo).position().left - 80) + 'px';
				canvasOverlay.style.zIndex = '10000';
				canvasOverlay.style.display = 'block';

				contextOverlay.beginPath();
				contextOverlay.rect(100, 0, 100, 100);
				contextOverlay.lineWidth = 3;
				contextOverlay.strokeStyle = 'white';
				contextOverlay.stroke();

				$("#instructions").hide();
			}
		});

		document.addEventListener("facetrackingEvent", function (event) {
			//фотография находитя в квадрате
			var previous = inRectangle;
			inRectangle = event.x >= 158 && event.x < 358;

			if (inRectangle && !previous) {
				$(document).trigger('faceInRectangle');
			}
			else if (!inRectangle && previous) {
				$(document).trigger('faceLostInRectangle');
			}

		});

		//событие - лицо попало в квадрат
		$(document).bind('faceInRectangle', function () {
			contextOverlay.strokeStyle = 'green';
			contextOverlay.stroke();

		});

		//событие - лицо покинуло квадрат
		$(document).bind('faceLostInRectangle', function () {
			contextOverlay.strokeStyle = 'white';
			contextOverlay.stroke();
		});
	});
</script>
</body>
</html>
