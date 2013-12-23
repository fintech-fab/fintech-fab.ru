<?php

class YaMetrikaGoalsWidget extends CWidget
{
	/**
	 * @var integer сделано шагов на данный момент
	 */
	private $sStep;

	public function run()
	{
		$this->sStep = Yii::app()->clientForm->getMetrikaGoalByStep();

		if (!empty($this->sStep)) {
			Yii::app()->clientScript->registerScript('yaMetrikaGoal' . $this->sStep, '
           yaCounter21390544.reachGoal("done_step_' . $this->sStep . '");
           ', CClientScript::POS_LOAD);
		}
	}
}
