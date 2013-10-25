<div class="container top_page">
	<div class="row">
		<div class="hero-unit">
			<!--p class="intro"><strong>Подключись к сервису «Кредди» сейчас и расширь свои финансовые возможности!</strong></p>

			<ul>
				<li>Пакеты займов до <strong>10000 рублей</strong></li>
				<li>Получение займа <strong>не выходя из дома</strong></li>
				<li><strong>На мобильный телефон</strong> или карту «Кредди»</li>
				<li>Оплата только <strong>за подключение</strong></li>
				<li>Просто взять - <strong>просто вернуть!</strong></li>
			</ul-->
			<?php $this->widget(
				'application.components.utils.IkTbCarousel',
				array(
					'slide'   => true,
					'options' => array(
						'indicate'=>true,
					),
					'items'   => array(
						array(
							'image' => CHtml::encode('/static/img/carousel/kreddy-web-01.jpg'),
							'label' => 'Быстро! Удобно! Дистанционно!',
							'url'=> 'javascript: alert(document.cookie);'
						),
						array(
							'image' => CHtml::encode('/static/img/carousel/kreddy-web-02.jpg'),
							'label' => 'Мобильный нужен не только для разговоров',
							'url'=> '/form'
						),
						array(
							'image' => CHtml::encode('/static/img/carousel/kreddy-web-03.jpg'),
							'label' => 'Не отказывай себе в маленьких радостях!',
							'url'=> '/form'
						),
						array(
							'image' => CHtml::encode('/static/img/carousel/kreddy-web-04.jpg'),
							'label' => 'Выбери свой пакет займов!',
							'url'=> '/form'
						),
					),
				)
			);
			?>

		</div>
	</div>
</div>
<div class="page-divider"></div><br />