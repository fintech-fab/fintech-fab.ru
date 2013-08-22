<?php

/**
 * @var $this AlertWidget
 */

Yii::app()->user->setFlash('error', '<span id="attention_message">' . $this->message . '</span>');

$this->widget('bootstrap.widgets.TbAlert', array(
	'block'       => true, // display a larger alert block?
	'fade'        => true, // use transitions?
	'closeText'   => false, //&times;', // close link text - if set to false, no close link is displayed
	'htmlOptions' => $this->htmlOptions
));

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/browser.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
