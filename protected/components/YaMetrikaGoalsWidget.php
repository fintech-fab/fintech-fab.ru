<?php

class YaMetrikaGoalsWidget extends CWidget
{
	public $iDoneSteps;

	public function run()
	{
		if ($this->iDoneSteps == 0) {
			return;
		}

		$this->iDoneSteps = ($this->iDoneSteps >= 3) ? ($this->iDoneSteps - 2) : $this->iDoneSteps;
		$this->render('ya_metrika_goals');
	}
}

?>
