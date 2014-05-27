<?php


class LinkprofitWidget extends CWidget
{

	public function init()
	{

	}

	public function run()
	{
		$sWid = Yii::app()->request->cookies['linkprofit'];

		if ($sWid) {
			Yii::app()->request->cookies->remove('linkprofit');

			$this->render('pixel', ['sWid' => $sWid->value, 'sOrderId' => time()]);
		}
	}

} 