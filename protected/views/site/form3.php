<?php
/* @var $this SiteController */
/* @var $model ClientForm2 */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name;
?>
<?php
$this->widget('TopPageWidget');
?>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php $this->widget('StepsBreadCrumbs', array(
				'curStep' => 4,
			)); ?>
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<div id="instructions" class="alert alert-info"><h5>Разрешите использование веб-камеры</h5></div>
		</div>

		<canvas id="inputCanvas" width="640" height="480" style="display: none"></canvas>
		<video id="inputVideo" width="320" height="240" autoplay loop style="-webkit-transform: scaleX(-1); transform: scaleX(-1);
 width: 500px; height: 500px;"></video>
		<canvas id="overlay" width="500" height="500"></canvas>

	</div>
</div>

<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/static/js/headtrackr.js"></script>

<script type="text/javascript">

	var video = document.getElementById('inputVideo');
	var canvas = document.getElementById('inputCanvas');
	var canvasOverlay = document.getElementById('overlay');
	var contextOverlay = canvasOverlay.getContext('2d');

	var instructions = $('#instructions');

	var inRectangle = false;
	var shootCountdown = null;
	var shootCountdownValue = null;

	//рисуем квадрат
	canvasOverlay.style.position = "absolute";
	canvasOverlay.style.top = '600px';
	canvasOverlay.style.zIndex = '1';
	canvasOverlay.style.display = 'block';

	contextOverlay.beginPath();
	contextOverlay.rect(158, 50, 200, 200);
	contextOverlay.lineWidth = 7;
	contextOverlay.strokeStyle = 'white';
	contextOverlay.stroke();

	//запускаем трекер лица
	var headTracker = new headtrackr.Tracker({
		ui: false
	});

	headTracker.init(video, canvas);
	headTracker.start();

	document.addEventListener('headtrackrStatus', function (event) {
		if (event.status == 'camera found') {
			instructions.text('Шаг 1. Разместите лицо в квадрате');
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

		clearInterval(shootCountdown);
		shootCountdownValue = null;

		instructions.text('Разместите лицо в квадрате');

	});

</script>