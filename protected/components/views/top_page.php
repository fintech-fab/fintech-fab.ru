<div class="container top_page">
	<div class="row">
		<div class="hero-unit">
			<?php
			if (!SiteParams::getIsIvanovoSite()) {
				$aItems = array(
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-02.jpg'),
						'label' => 'Без лишних свидетелей',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-03.jpg'),
						'label' => 'Всегда при деньгах',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-04.jpg'),
						'label' => 'Позволь себе больше!',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-05.jpg'),
						'label' => 'Без суеты и волокиты',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-06.jpg'),
						'label' => 'Сколько занял - столько и вернул',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-01.jpg'),
						'label' => 'В любом месте, в любое время',
						'url'   => '/pages/view/smsinfo',
					),
				);
			} else {
				$aItems = array(
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/ivanovo.jpg'),
						'label' => 'Как оплатить счет?!',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-01.jpg'),
						'label' => 'Быстро! Удобно! Дистанционно!',
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-02.jpg'),
						'label' => 'Мобильный нужен не только для разговоров',
						'url'   => '/pages/view/check'
					),
					array(
						'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-web-05_ivanovo.jpg'),
						'label' => 'Все полезные контакты сервиса "Кредди" всегда под рукой!',
					),
				);
			}


			$this->widget(
				'application.components.utils.IkTbCarousel',
				array(
					'slide'              => true,
					'displayPrevAndNext' => false,
					'options'            => array(
						'interval' => 5000
					),
					'items'              => $aItems
				)
			);


			//TODO сделать управление виджетом из админки: добавление картинок, ссылки и прочее
			?>

		</div>
	</div>
</div>
<div class="page-divider"></div><br />
