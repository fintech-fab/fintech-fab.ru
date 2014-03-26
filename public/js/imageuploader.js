$(document).ready(function () {
	$('input[type=file]').each(function (x) {
		var form = $('<form action="upload/image" method="post" enctype="multipart/form-data" target="iframe-name'
			+ x + '"></form>');
		$(this).before('<iframe name="iframe-name' + x + '" src="#"></iframe>').wrap(form).delay(1500).change(function () {
			$(this).parent().submit().prev().one('load',
				function () {
					$(this).next()[0].reset(); //очищает инпут для того что бы не сабмитить файл в родительской форме
					var answer = ($(this).contents().find('body').html());
					if (answer == '1') {
						afterUpload();
					} else {
						dropZoneText.text('Ошибка, попробуйте ещё раз');
						dropZone.addClass('error');
					}
				});
		});
	});
	function afterUpload() {
		$('#photo').load("widgets/getPhoto").show();
		$('#drop-files').hide();
	}

	var dropZone = $('#dropZone'),
		dropZoneText = $('#dropZone .text')
	maxFileSize = 2000000; // максимальный размер фалйа - 2 мб.

	// Проверка поддержки браузером
	if (typeof(window.FileReader) == 'undefined') {
		dropZoneText.text('Не поддерживается браузером!');
		dropZone.addClass('error');
	}

	// Добавляем класс hover при наведении
	dropZone[0].ondragover = function () {
		dropZone.addClass('hover');
		return false;
	};

	// Убираем класс hover
	dropZone[0].ondragleave = function () {
		dropZone.removeClass('hover');
		return false;
	};

	// Обрабатываем событие Drop
	dropZone[0].ondrop = function (event) {
		event.preventDefault();
		dropZone.removeClass('hover');
		dropZone.addClass('drop');

		var file = event.dataTransfer.files[0];

		// Проверяем размер файла
		if (file.size > maxFileSize) {
			dropZoneText.text('Файл слишком большой!');
			dropZone.addClass('error');
			dropZone.removeClass('hover drop');
			return false;
		}

		// Создаем запрос
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener('progress', uploadProgress, false);
		xhr.onreadystatechange = stateChange;
		xhr.open('POST', 'upload/image');
		xhr.setRequestHeader('X-FILE-NAME', file.name);
		xhr.send(file);
	};


	// Показываем процент загрузки
	function uploadProgress(event) {
		var percent = parseInt(event.loaded / event.total * 100);
		dropZone.text('Загрузка: ' + percent + '%');
	}

	// Пост обрабочик
	function stateChange(event) {
		if (event.target.readyState == 4) {
			if (event.target.status == 200) {
				dropZoneText.text('Загрузка успешно завершена!');
			} else {
				dropZoneText.text('Произошла ошибка!');
				dropZone.addClass('error');
				dropZone.removeClass('hover drop');
			}
		}
	}

});
