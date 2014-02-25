<?php
/**
* 
*/
class ProvisionsCommand extends CConsoleCommand
{
	public function run($args)
	{
		if(isset($args[0]))
		{
	    	Yii::app()->provisions->run($args[0]);
		}
		else
		{
	    	Yii::app()->provisions->run();
		}
	}
}
?>