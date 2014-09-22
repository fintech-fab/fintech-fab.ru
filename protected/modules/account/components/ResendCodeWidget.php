<?php

/**
 * Class ResendCodeWidget
 */
class ResendCodeWidget extends CWidget
{
	public $sUrl = '/form/resend';
	public $sId = 'Resend';
	public $sResendText = 'Повторно запросить код можно через:';
	public $sButtonText = 'Выслать код еще раз';
	public $iTime = 0;

	public function run()
	{
		?>
		<div id="divUntilResend<?= $this->sId ?>">
			<?php // отрисовываем скрипт
			$this->renderScript();
			$this->renderButton(); ?>
			<div id="textUntilResend<?= $this->sId ?>">
				<?= $this->sResendText; ?>
				<span id="untilResend<?= $this->sId ?>"></span>
			</div>
		</div>

		<script lang="javascript">
			leftTime<?= $this->sId ?> = new Date();
			leftTime<?= $this->sId ?>.setTime(leftTime<?= $this->sId ?>.getTime() + <?= $this->getTime() ?> * 1000);
			showUntilResend<?= $this->sId ?>();
		</script>
	<?php
	}

	protected function renderButton()
	{
		$this->widget('bootstrap.widgets.TbButton', array(
			'id'          => 'btn' . $this->sId,
			'buttonType'  => 'ajaxButton',
			'icon'        => 'icon-refresh',
			'size'        => 'small',
			'label'       => $this->sButtonText,
			'disabled'    => true,
			'url'         => Yii::app()->createUrl($this->sUrl),
			'ajaxOptions' => array(
				'type'     => 'POST',
				'cache'    => false,
				'dataType' => 'html',
				'data'     => array(
					Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
				),
				'success'  => ' function(html){
					$("#divUntilResend' . $this->sId . '").html(html);
				}'

			),

		));

	}

	protected function renderScript()
	{
		?>
		<script lang="javascript">
			var leftTime<?= $this->sId ?>;
			function showUntilResend<?= $this->sId ?>() {
				iSecondsLeft = Math.floor((leftTime<?= $this->sId ?> - (new Date())) / 1000);
				if (iSecondsLeft < 0) {
					jQuery("#btn<?= $this->sId; ?>").removeAttr("disabled").removeClass("disabled");
					jQuery("#textUntilResend<?= $this->sId; ?>").hide();
					return;
				}
				jQuery("#textUntilResend<?= $this->sId ?>").show();
				iMinutesLeft = Math.floor(iSecondsLeft / 60);
				iSecondsLeft -= iMinutesLeft * 60;
				if (iSecondsLeft < 10) {
					iSecondsLeft = "0" + iSecondsLeft;
				}
				jQuery("#untilResend<?= $this->sId ?>").html(iMinutesLeft + ":" + iSecondsLeft);
				setTimeout(showUntilResend<?= $this->sId ?>, 1000);
			}
		</script>
	<?php
	}

	/**
	 * @return int
	 */
	protected function getTime()
	{
		return $this->iTime;
	}
}