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
		$date='2013-12-01';
		$final='2013-12-15';
		while ($date <= $final)
		{
			Yii::app()->transfer->run($date);
			$date=DateManagement::calculateDate('+1',$date);
		}
	}
}
?>