<?php
/* @var $this FormController */

$this->pageTitle = Yii::app()->name;
?>
<?php
$this->widget('TopPageWidget');
?>
	<div class="row">
		<div class="span12">
			<div id="instructions" class="alert alert-info" style="font-weight: bold; font-size: 1.5em; padding: 10px;">
				Разрешите использование
				веб-камеры
			</div>
		</div>

		<div class="span12">
			<div class="row">
				<canvas id="inputCanvas" width="640" height="480" style="display: none"></canvas>
				<video id="inputVideo" class="span6" width="100%" autoplay loop
				       style="height: 400px; -webkit-transform: scaleX(-1); transform: scaleX(-1);"></video>
				<canvas id="overlay" class="span6"></canvas>
			</div>
		</div>

	</div>

<script type="text/javascript" src="<?= Yii::app()->request->baseUrl ?>/static/js/headtrackr.js"></script>

<script type="text/javascript">

	$(function () {
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
		canvasOverlay.style.top = parseInt($(inputVideo).position().top + 110) + 'px';
		canvasOverlay.style.display = 'block';

		contextOverlay.beginPath();
		contextOverlay.rect(98, 0, 120, 120);
		contextOverlay.lineWidth = 3;
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

			shootCountdown = setInterval(function () {

				if (shootCountdownValue === null) {
					shootCountdownValue = 2;
				}
				else {
					shootCountdownValue--;
				}

				instructions.text(shootCountdownValue + '...');

				if (shootCountdownValue === 0) {
					instructions.text('Ждите, идет отправка данных на сервер');

					headTracker.stop();

					processPhoto();
					clearInterval(shootCountdown);
					shootCountdownValue = null;
				}

			}, 1000);

		});

		//событие - лицо покинуло квадрат
		$(document).bind('faceLostInRectangle', function () {
			contextOverlay.strokeStyle = 'white';
			contextOverlay.stroke();

			clearInterval(shootCountdown);
			shootCountdownValue = null;

			instructions.text('Шаг 1. Разместите лицо в квадрате');

		});

		function processPhoto() {

			var dataURL = canvas.toDataURL();
			$.post('/image/processPhoto', { image: dataURL, type: '<?=ImageController::C_TYPE_PHOTO?>', YII_CSRF_TOKEN: '<?=Yii::app()->request->csrfToken?>' }, function (response) {

				var faceCount = parseInt(response);

				if (faceCount === 1) {
					instructions.html('Данные успешно сохранены <a href="/form/documents" class="btn">продолжить</a>');

					contextOverlay.clearRect(0, 0, canvas.width, canvas.height);
					headTracker.stop();
				}
				else {
					instructions.text('Возникла ошибка, попробуйте снова');

					setTimeout(function () {
						headTracker.start();

						inRectangle = false;
						$(document).trigger('faceLostInRectangle');
					}, 2000);

				}
			});
		}
	});
</script>
