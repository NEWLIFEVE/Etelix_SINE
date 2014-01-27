<?php
/**
* 
*/
class LoopCommand extends CConsoleCommand
{
	public function run($args)
	{
		$date='2013-07-02';
		$final='2014-01-24';
		while ($date <= $final)
		{
			Yii::app()->provisions->run($date);
			$date=DateManagement::calculateDate('+1',$date);
		}
	    //
	}
}
?>