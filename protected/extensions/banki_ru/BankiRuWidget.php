<?php


class BankiRuWidget extends CWidget
{

	public function init()
	{

	}

	public function run()
	{
		$sWid = Yii::app()->request->cookies['banki_ru'];

		if ($sWid) {
			Yii::app()->request->cookies->remove('banki_ru');

			$this->render('pixel', ['iSubId' => time()]);
		}
	}

} 