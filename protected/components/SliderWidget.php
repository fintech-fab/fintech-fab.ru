<?php
/**
 * Class SliderWidget
 */

class SliderWidget extends CWidget
{

	public function run()
	{

		$model = new ClientFlexibleProductForm();

		$sAssetsPath = Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('ext.selectToUiSlider.assets') . '/');

		Yii::app()->clientScript->registerScriptFile($sAssetsPath.'/js/selectToUISlider.jQuery.js', CClientScript::POS_HEAD);

		Yii::app()->clientScript->registerCssFile($sAssetsPath.'/css/ui.slider.extras.css');
		Yii::app()->clientScript->registerCssFile($sAssetsPath.'/css/redmond/jquery-ui-1.7.1.custom.css');

		$this->render('slider_widget', array('model'=>$model,'aSelectValues'=>Yii::app()->adminKreddyApi->getFlexibleProduct()));
	}

}