<?php
/**
* 
*/
class LoopCommand extends CConsoleCommand
{
	public function run($args)
	{
		$carrier=null;
		$date=$args[0];
		$final=$args[1];
		if(isset($args[2])) $carrier=$args[2];
		while ($date <= $final)
		{
			Yii::app()->provisions->run($date,$carrier);
			$date=DateManagement::calculateDate('+1',$date);
		}
	}
}
?>
