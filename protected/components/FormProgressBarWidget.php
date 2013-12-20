<?php

/**
 * Class FormProgressBarWidget
 */
class FormProgressBarWidget extends CWidget
{
	public $aSteps;
	public $iCurrentStep;

	public function run()
	{
		//очистка эвентов для ajaxLink (для ajax-загрузки страницы с прогрессбаром)
		Yii::app()->clientScript->registerScript('progressBar', '
		$links = $("#progressBar a");
			$.each($links,function()
			{
				id = $(this).attr("id");
				$("body").off("click","#"+id);
			});
		');

		echo '<div id="progressBar">';
		$iSteps = sizeof($this->aSteps);
		if ($iSteps > 0) {
			$fPercent = 100 / $iSteps;
			$iPercent = (int)$fPercent;
			$iProgress = (int)($this->iCurrentStep * $fPercent) - 18;
			echo '<div class="row" style="margin-right: 20px;">';

			foreach ($this->aSteps as $iKey => $aStep) {
				echo '<span style="width: ' . $iPercent . '%" class="pull-left">';
				if ($iKey < $this->iCurrentStep) {
					echo '<i class="icon-ok"></i>';
					echo CHtml::ajaxLink(
						$aStep['label'],
						array($aStep['url'])
						, array(
							'update' => '#formBody',
						));
				} elseif ($iKey == $this->iCurrentStep) {
					echo '<i class="icon-user"></i>';
					echo $aStep['label'];
				} else {
					echo $aStep['label'];
				}
				echo '</span>';
			}

			echo '</div>';
			echo '<div class="row">';

			$this->widget('bootstrap.widgets.TbProgress', array(
				'type'        => 'danger', // 'info', 'success' or 'danger'
				'percent'     => $iProgress, // the progress
				'striped'     => true,
				'animated'    => true,
				'htmlOptions' => array(
					'style' => 'height: 5px; margin-right: 20px;',
				),
			));
			echo '</div>';
			echo '</div>';
		}
	}
}

