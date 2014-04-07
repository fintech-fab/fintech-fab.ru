<?php

/**
 * @var $this CheckBrowserWidget
 */

?>

	<div class="alert in alert-block alert-error hide" id="browserFormat">
	<span id="attention_message">
		<?= $this->sMessage ?>
	</span> <br /><br /> <span id="your_browser"></span>

		<?php if ($this->bShowBrowsersLink): ?>
			<div id="get_browser" style="margin-top:10px;"><a href="<?=
				Yii::app()
					->createUrl('/pages/view/browser') ?>" target="_blank">Перейти на страницу загрузки поддерживаемого
					браузера &raquo;</a>
			</div>
		<?php endif; ?>
	</div>
<?php

Yii::app()->clientScript->registerScript('messageForBrowser', '
	var sMessage = "' . $this->sMessage . '";
	var sMobileMessage = "' . $this->sMobileMessage . '";
', CClientScript::POS_BEGIN);
$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/browser.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_HEAD);
