<?php

/**
 * Class CarouselWidget
 */
class CarouselWidget extends CWidget
{

	public function run()
	{


		$aItems = array(
			array(
				'image' => CHtml::encode('/static/img/kreddyline-carousel/banner1.png'),
				'label' => 'КРЕДДИтная линия',
			),
			array(
				'image' => CHtml::encode('/static/img/kreddyline-carousel/banner2.png'),
				'label' => 'КРЕДДИтная линия',
			),
			array(
				'image' => CHtml::encode('/static/img/kreddyline-carousel/banner3.png'),
				'label' => 'КРЕДДИтная линия',
			),
		);
		?>
		<div class="container carouselWidget">
			<div class="row">
				<?php

				$this->widget(
					'application.components.utils.IkTbCarousel',
					array(
						'slide'              => true,
						'displayPrevAndNext' => false,
						'options'            => array(
							'interval' => 5000
						),
						'items'              => $aItems,
						'htmlOptions'        => array(
							'style' => 'margin-bottom: 0; width: 1234px; height: 450px;',
						)
					)
				);

				//TODO сделать управление виджетом из админки: добавление картинок, ссылки и прочее
				?>


			</div>
		</div>
	<?php

	}
}
