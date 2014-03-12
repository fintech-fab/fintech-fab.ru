function browser() {
	var ua = navigator.userAgent;

	if (ua.search(/MSIE/) > 0) return 'Internet Explorer';
	if (ua.search(/Firefox/) > 0) return 'Firefox';
	if (ua.search(/Opera/) > 0) return 'Opera';
	if (ua.search(/Chrome/) > 0) return 'Google Chrome';
	if (ua.search(/Safari/) > 0) return 'Safari';
	if (ua.search(/Konqueror/) > 0) return 'Konqueror';
	if (ua.search(/Iceweasel/) > 0) return 'Debian Iceweasel';
	if (ua.search(/SeaMonkey/) > 0) return 'SeaMonkey';

	// Браузеров очень много, все вписывать смысле нет, Gecko почти везде встречается
	if (ua.search(/Gecko/) > 0) return 'Gecko';

	// а может это вообще поисковый робот
	return 'Search Bot';
}


ymaps.ready(init);

function init() {
	var myMap = new ymaps.Map("map", {
			center: [55.72074, 37.65098],
			zoom: 15
		}),

	// Создаем геообъект с типом геометрии "Точка".
		myGeoObject = new ymaps.GeoObject({
			// Описание геометрии.
			geometry: {
				type: "Point",
				coordinates: [55.72074, 37.65098]
			},
			// Свойства.
			properties: {
				// Контент метки.
				iconContent: 'БЦ "Полларс"<br>ОФИС FINTECH_FAB',
				balloonContent: 'Дербеневская наб., 11<br/>БЦ&nbsp;"Полларс"<br>Офис FINTECH_FAB<br><a href="http://maps.yandex.ru/-/CVV26EZu" target="_blank">Открыть в Яндекс.Картах</a><br/><a href="http://maps.yandex.ru/-/CVVGFF-N" target="_blank">Путь от метро</a>'
			}
		}, {
			// Опции.
			// Иконка метки будет растягиваться под размер ее содержимого.
			preset: 'twirl#blueStretchyIcon',
			// Метку можно перемещать.
			draggable: false
		});

	if (browser() == 'Internet Explorer') {
		myMap.behaviors.disable(['drag', 'rightMouseButtonMagnifier', 'dblClickZoom', 'leftMouseButtonMagnifier']);
		var cursor = myMap.cursors.push('arrow');
	}
	// Создаем метку с помощью вспомогательного класса.

	// Добавляем все метки на карту.
	myMap.geoObjects.add(myGeoObject);
}
// поставим курсор "стрелка" над картой
