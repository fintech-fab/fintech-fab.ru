<?php

class BottomTabs extends CWidget
{
	public $tabs;
	public $tabsArray;

	public function run()
	{
		$first=true;
		foreach($this->tabs as &$t)
		{
			if ($first)
			{
				$this->tabsArray[]=array('label'=>$t->tab_title,'content'=>$t->tab_content,'active'=>true);
				$first=false;
			}
			else
			{
				$this->tabsArray[]=array('label'=>$t->tab_title,'content'=>$t->tab_content);
			}
		}
		$this->render('bottomtabs');
	}
}
?>