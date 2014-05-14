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
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy2014-03.jpg'),
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

			/*array(
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
			),*/
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
