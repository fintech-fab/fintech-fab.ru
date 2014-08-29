<?php


/**
 * Class PixelWidget
 */
class PixelWidget extends CWidget
{

	public function init()
	{

	}

	public function run()
	{
		$oCookie = Yii::app()->request->cookies['lidogenerator'];

		if ($oCookie) {
			Yii::app()->request->cookies->remove('lidogenerator');

			if (array_key_exists($oCookie->value, PixelFilter::$aAdditionalFields)) {
				$this->render($oCookie->value, ['oParams' => $oCookie, 'sOrderId' => time()]);
			}
		}
	}

} 