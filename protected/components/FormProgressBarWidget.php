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
							//снимаем все эвенты с кнопки, т.к. после загрузки ajax-ом содержимого эвент снова повесится на кнопку
							//сделано во избежание навешивания кучи эвентов
							//'complete' => 'jQuery("body").off("click","#' . get_class($oClientCreateForm) . '_submitButton")',
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
		}
	}
}

