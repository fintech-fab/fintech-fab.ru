<?php
/**
 * Class YaMetrikaGoalsWidget
 */
class YaMetrikaGoalsWidget extends CWidget
{
	/**
	 * @var integer сделано шагов на данный момент
	 */
	private $sStep;
	public $sForceGoal = null;

	public function run()
	{
		$this->sStep = Yii::app()->clientForm->getMetrikaGoalByStep();

		//если цель установлена "насильно", то выводим её
		if ($this->sForceGoal) {
			$this->sStep = $this->sForceGoal;
		}

		if (!empty($this->sStep)) {
			Yii::app()->clientScript->registerScript('yaMetrikaGoal' . $this->sStep, '
           yaCounter21390544.reachGoal("' . $this->sStep . '");
           ', CClientScript::POS_LOAD);
		}
	}
}
