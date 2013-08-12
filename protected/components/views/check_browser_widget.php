<?php

/**
 * @var $this CheckBrowserWidget
 */

Yii::app()->user->setFlash('error', $this->sMessage . '
<br/><br/><span id="your_browser"></span>
<br/><br/><a href="/pages/view/browser">Перейти на страницу загрузки поддерживаемого браузера &raquo;</a>');

$this->widget('bootstrap.widgets.TbAlert', array(
	'block'       => true, // display a larger alert block?
	'fade'        => false, // use transitions?
	'closeText'   => '&times;', // close link text - if set to false, no close link is displayed
	'htmlOptions' => array_merge(
		array(
			'id'    => 'browserFormat',
			'class' => 'hide',
		), $this->aHtmlOptions
	),
));

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/browser.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
