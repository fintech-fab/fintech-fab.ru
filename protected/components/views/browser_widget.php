<?php

Yii::app()->user->setFlash('error', '<strong>Внимание!</strong> После заполнения анкеты Вы можете пройти
видеоидентификацию, которая работает только в браузерах <strong>Chrome</strong> и <strong>Firefox</strong>
последних версий.<span id="your_browser"></span>');

$this->widget('bootstrap.widgets.TbAlert', array(
	'block'=>true, // display a larger alert block?
	'fade'=>false, // use transitions?
	'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
	'htmlOptions'=>array(
		'id'=>'browserFormat',
		'class'=>'hide',
	),
));

$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets').'/').'/js/browser.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
