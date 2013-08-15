<?php

class YaMetrikaGoalsWidget extends CWidget
{
	/**
	 * @var integer сделано шагов на данный момент
	 */
	public $iDoneSteps;

	/**
	 * @var integer вычесть из числа шагов (засчёт видеоидентификации)
	 */
	public $iSkippedSteps;

	public function run()
	{
		$this->iDoneSteps = (int)$this->iDoneSteps;
		$this->iDoneSteps = (!empty($this->iSkippedSteps)) ? ($this->iDoneSteps - (int)$this->iSkippedSteps) : $this->iDoneSteps;
		$this->render('ya_metrika_goals');
	}
}

?>
