<?php
/**
 * @var $this YaMetrikaGoalsWidget
 */


Yii::app()->clientScript->registerScript('yaMetrikaGoals', '
           yaCounter21390544.reachGoal("done_step_' . $this->iDoneSteps . '");
', CClientScript::POS_LOAD);

