<?php
/**
* 
*/
class TransCommand extends CConsoleCommand
{
	/**
	 *
	 */
	public function run($args)
	{
		$date='2014-01-01';
		$final='2014-01-06';
		while ($date <= $final)
		{
			Yii::app()->transfer->run($date);
			$date=DateManagement::calculateDate('+1',$date);
		}
	}
}
?>