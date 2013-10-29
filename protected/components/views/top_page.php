<div class="container top_page">
	<div class="row">
		<div class="hero-unit">
			<?php $this->widget(
				'application.components.utils.IkTbCarousel',
				array(
					'slide'   => true,
					'displayPrevAndNext'=>false,
					'options'=>array(
						'interval'=>5000
					),
					'items'   => array(
						array(
							'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-01.jpg'),
							'label' => 'Быстро! Удобно! Дистанционно!',
						),
						array(
							'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-02.jpg'),
							'label' => 'Мобильный нужен не только для разговоров',
							'url'=> '/pages/view/check'
						),
						array(
							'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-03.jpg'),
							'label' => 'Не отказывай себе в маленьких радостях!',
							'url'=> '/form/shopping'
						),
						array(
							'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-04.jpg'),
							'label' => 'Выбери свой пакет займов!',
						),
					),
				)
			);
			?>

		</div>
	</div>
</div>
<div class="page-divider"></div><br />