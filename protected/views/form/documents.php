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
			<video id="inputVideo" class="span8" width="100%" autoplay loop
			       style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"></video>

			<div class="span4">
				<img id="exampleImage" width="100%" style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"/><br/><br/>

				<button data-toggle="modal" data-target="#confirm-modal" id="shot-button" class="btn btn-primary"
				        style="display: none;">сфотографировать
				</button>
			</div>
		</div>
	</div>

</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'confirm-modal', 'fade' => false)); ?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
</div>

<div class="modal-body">
	<img id="resultImage" width="100%" style="-webkit-transform: scaleX(-1); transform: scaleX(-1);"/>
</div>

<div class="modal-footer">
	<div id="confirm_text" class="alert alert-warning" style="text-align: left"></div>

	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'label'       => 'Закрыть',
		'url'         => '#',
		'htmlOptions' => array('data-dismiss' => 'modal'),
	)); ?>

	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'primary',
		'label'       => 'Продолжить',
		'url'         => '#',
		'htmlOptions' => array(
			'data-dismiss' => 'modal',
			'onClick'      => 'confirmImage()',
		),
	)); ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	var currentDocument = {
		type: '<?=ImageController::C_TYPE_PASSPORT_FRONT_FIRST?>',
		title: '<?=ImageController::C_TYPE_PASSPORT_FRONT_FIRST?>',
		example: '<?=ImageController::$aTypes[ImageController::C_TYPE_PASSPORT_FRONT_FIRST]['example']?>',
		instructions: '<?=ImageController::$aTypes[ImageController::C_TYPE_PASSPORT_FRONT_FIRST]['instructions']?>',
		confirm_text: '<?=ImageController::$aTypes[ImageController::C_TYPE_PASSPORT_FRONT_FIRST]['confirm_text']?>'
	};

	var shotButton = $('#shot-button');
	var confirmShotButton = $('#confirm-shot-button');
	var instructions = $('#instructions');
	var confirm_text = $('#confirm_text');

	var video = document.querySelector('#inputVideo');
	var canvas = document.querySelector('#inputCanvas');
	var ctx = canvas.getContext('2d');
	var localMediaStream = null;

	var exampleImage = $('#exampleImage');
	var resultImage = $('#resultImage');

	var onCameraFail = function (e) {
		console.log('Camera did not work.', e);
	};

	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
	window.URL = window.URL || window.webkitURL;

	navigator.getUserMedia({video: true}, function (stream) {
		video.src = window.URL.createObjectURL(stream);
		localMediaStream = stream;

		instructions.text(currentDocument.instructions);
		exampleImage.attr('src', currentDocument.example);

		shotButton
			.show()
			.click(captureImage);

	}, onCameraFail);

	function captureImage() {

		if (localMediaStream) {
			ctx.drawImage(video, 0, 0);

			var dataURL = canvas.toDataURL();

			resultImage.attr('src', dataURL);

			shotButton
				.text('Переснять');

			confirm_text.text(currentDocument.confirm_text).show();

			confirmShotButton.show();
		}
	}

	function confirmImage() {
		shotButton
			.attr('disabled', 'disabled')
			.text('Пожалуйста, подождите...');

		confirmShotButton.hide();
		confirm_text.hide();

		console.log(currentDocument.type);
		$.post('/image/processPhoto', { image: exampleImage.attr('src'), type: currentDocument.type, '<?= Yii::app()->request->csrfTokenName ?>': '<?=Yii::app()->request->csrfToken?>'}, function (response) {

				var json = $.parseJSON(response);

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
				currentDocument = {
					type: json.next_type.type,
					title: json.next_type.title,
					example: json.next_type.example,
					instructions: json.next_type.instructions,
					confirm_text: json.next_type.confirm_text
				};

				instructions.text(currentDocument.instructions);
				exampleImage.attr('src', currentDocument.example);

			}
		)
		;
	}

</script>
