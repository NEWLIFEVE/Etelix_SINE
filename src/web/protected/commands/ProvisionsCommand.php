<?php
/**
* 
*/
class ProvisionsCommand extends CConsoleCommand
{
	public function run($args)
	{
	    Yii::app()->provisions->run($args[0]);
	}
}
?>