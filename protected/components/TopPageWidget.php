<?php

/**
 * Class TopPageWidget
 */
class TopPageWidget extends CWidget
{

	public $show = false; // показывать ли на странице

	public function run()
	{
		$aItems = array(
			array(
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-02.jpg'),
				'label' => 'КРЕДДИтная линия',
			),
			array(
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-05.jpg'),
				'label' => 'КРЕДДИтная линия',
			),
			array(
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-06.jpg'),
				'label' => 'КРЕДДИтная линия',
			),
		);

		if ($this->show) {
			?>
			<div class="container top_page">
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
								'style' => 'margin-bottom: 0; width: 930px; margin-left: 15px;',
							)

						)
					);


					//TODO сделать управление виджетом из админки: добавление картинок, ссылки и прочее
					?>

				</div>
			</div>
			<div class="page-divider"></div>
		<?php
		}
	}
}
