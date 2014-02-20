<?php
/**
* 
*/
class LoopCommand extends CConsoleCommand
{
	public function run($args)
	{
		$date=$args[0];
		$final=$args[1];
		while ($date <= $final)
		{
			Yii::app()->provisions->run($date);
			$date=DateManagement::calculateDate('+1',$date);
		}
	}
}
?>
