<?php /* @var $this Controller */

?>

<div class="row">
	<div class="span8">
		<h3 class="pay_legend">Личный кабинет</h3><br />
		<?php echo $content; ?>
	</div>
	<!-- content -->
	<div class="span4">
		<?php
		(Yii::app()->adminKreddyApi->isSmsAuth())
			? $this->renderPartial('menu_is_sms_auth')
			: $this->renderPartial('menu_not_sms_auth');
		?>
	</div>
</div>