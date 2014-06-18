<?php


/**
 * Class LinkprofitWidget
 */
class LinkprofitWidget extends CWidget
{

	public function init()
	{

	}

	public function run()
	{
		$sWid = Yii::app()->request->cookies['linkprofit'];

		if ($sWid) {
			Yii::app()->request->cookies['linkprofit'] = null;

			$this->render('pixel', ['sWid' => $sWid->value, 'sOrderId' => time()]);
		}
	}

} 