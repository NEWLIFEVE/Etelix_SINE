<?php
/**
* 
*/
class LoopCommand extends CConsoleCommand
{
	public function run($args)
	{
		$date='2013-10-02';
		$final='2014-01-07';
		while ($date <= $final)
		{
			Yii::app()->provisions->run($date);
			$date=DateManagement::calculateDate('+1',$date);
		}
	    //
	}
}
?>