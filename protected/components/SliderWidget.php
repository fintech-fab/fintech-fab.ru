<?php
/**
 * Class SliderWidget
 */

class SliderWidget extends CWidget
{

	public function run()
	{

		$sAssetsPath = Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('ext.selectToUiSlider.assets') . '/');

		Yii::app()->clientScript->registerScriptFile($sAssetsPath.'/js/selectToUISlider.jQuery.js', CClientScript::POS_HEAD);

		Yii::app()->clientScript->registerCssFile($sAssetsPath.'/css/ui.slider.extras.css');

		$this->render('slider_widget', array('aSelectValues'=>Yii::app()->adminKreddyApi->getFlexibleProduct()));
	}

}