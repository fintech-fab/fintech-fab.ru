<?php
/**
 * Class SliderWidget
 */

class SliderWidget extends CWidget
{
	public $form;
	public $model;
	public $bIsNeedNewClientAlert = true; //показывать ли предупреждение, что сумма доступна только постоянным клиентам

	public function run()
	{


		$sAssetsPath = Yii::app()->assetManager->publish(
			Yii::getPathOfAlias('ext.selectToUiSlider.assets') . '/');

		Yii::app()->clientScript->registerScriptFile($sAssetsPath . '/js/selectToUISlider.jQuery.js', CClientScript::POS_HEAD);

		Yii::app()->clientScript->registerCssFile($sAssetsPath . '/css/ui.slider.extras.css');
		Yii::app()->clientScript->registerCssFile($sAssetsPath . '/css/redmond/jquery-ui-1.7.1.custom.css');

		$this->render('slider_widget', array(
			'model' => $this->model,
			'aAmountValues' => Yii::app()->adminKreddyApi->getFlexibleProduct(),
			'aTimeValues'   => Yii::app()->adminKreddyApi->getFlexibleProductTime(),
			'aPercentage'   => Yii::app()->adminKreddyApi->getFlexibleProductPercentage(),
			'aChannelCosts' => Yii::app()->adminKreddyApi->getFlexibleProductChannelCosts(),
			'bIsNeedNewClientAlert' => $this->bIsNeedNewClientAlert,
			'form'          => $this->form
		));
	}

}