<?php
/**
 * 
 */
class ProvisionsCommand extends CConsoleCommand
{
	public function run($args)
	{
		$group=$date=null;
		if(isset($args[0])) $date=$args[0];
		if(isset($args[1])) $group=$args[1];
		Yii::app()->provisions->run($date,$group);
	}
}
?>
