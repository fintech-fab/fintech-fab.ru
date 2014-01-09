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
	public $bRegisterComplete;

	public function run()
	{
		$this->sStep = Yii::app()->clientForm->getMetrikaGoalByStep();

		//если текущее представление form_sent, то регистрация завершена
		if ($this->bRegisterComplete) {
			$this->sStep = 'register_complete';
		}


		if (!empty($this->sStep)) {
			Yii::app()->clientScript->registerScript('yaMetrikaGoal' . $this->sStep, '
           yaCounter21390544.reachGoal("' . $this->sStep . '");
           ', CClientScript::POS_LOAD);
		}
	}
}
