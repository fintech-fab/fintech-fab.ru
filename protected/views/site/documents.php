<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<?php
$this->widget('TopPageWidget');
?>

<div class="container">
	<div class="row">
		<div class="span12">
			<?php $this->widget('StepsBreadCrumbs', array(
				'curStep' => 5,
			)); ?>
		</div>
	</div>
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
				       style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"></video>

				<img id="resultImage" class="span6" style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"/>
			</div>
			<br/>

			<div class="row">
				<div class="span12">
					<button id="shot-button" class="btn btn-primary" style="display: none;">сфотографировать</button>
					<button id="confirm-shot-button" class="btn" style="display: none">продолжить</button>
				</div>
			</div>
		</div>

	</div>
</div>


<script type="text/javascript">
	var currentType = '<?=ImageController::C_TYPE_PASSPORT_FRONT?>';
	var currentTypeInstructions = '<?=ImageController::$aTypes[ImageController::C_TYPE_PASSPORT_FRONT]['description']?>';

	var shotButton = $('#shot-button');
	var confirmShotButton = $('#confirm-shot-button');
	var instructions = $('#instructions');

	var video = document.querySelector('#inputVideo');
	var canvas = document.querySelector('#inputCanvas');
	var ctx = canvas.getContext('2d');
	var localMediaStream = null;

	var resultImage = $('#resultImage');

	var onCameraFail = function (e) {
		console.log('Camera did not work.', e);
	};

	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
	window.URL = window.URL || window.webkitURL;

	navigator.getUserMedia({video: true}, function (stream) {
		video.src = window.URL.createObjectURL(stream);
		localMediaStream = stream;

		instructions.text(currentTypeInstructions);

		shotButton
			.show()
			.click(captureImage);

	}, onCameraFail);

	function captureImage() {

		if (localMediaStream) {
			ctx.drawImage(video, 0, 0);

			var dataURL = canvas.toDataURL();

			resultImage.attr('src', dataURL);

			confirmShotButton.show().click(confirmImage);
		}
	}

	function confirmImage() {
		shotButton
			.attr('disabled', 'disabled')
			.text('Пожалуйста, подождите...');

		confirmShotButton.hide();

		$.post('/image/processPhoto', { image: resultImage.attr('src'), type: currentType, YII_CSRF_TOKEN: '<?=Yii::app()->request->csrfToken?>' }, function (json) {

			json = $.parseJSON(json);

			shotButton
				.removeAttr('disabled')
				.text('Сфотографировать');

			//noinspection JSUnresolvedVariable
			if (json.next_type === null) {
				instructions.text('Все документы загружены');

				setTimeout(function () {
					window.location.href = '<?=Yii::app()->createUrl("pages/view",array('name'=>'formsent'));?>';
				}, 2000);

				shotButton.hide();

				return;
			}

			//noinspection JSUnresolvedVariable
			currentType = json.next_type.id;
			//noinspection JSUnresolvedVariable
			currentTypeInstructions = json.next_type.title;

			instructions.text(currentTypeInstructions);

			confirmShotButton.show();


		});
	}

</script>
