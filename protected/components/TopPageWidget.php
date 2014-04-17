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
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-line-banner.png'),
				'label' => 'КРЕДДИтная линия',
			),
			array(
				'image' => CHtml::encode('/static/img/kreddy-carousel/kreddy-line-banner2.png'),
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
			$this->render('top_page', array('aItems' => $aItems));
		}
	}
}
