<?php
/**
* 
*/
class deleteFifteenDays extends CConsoleCommand
{
	/**
	 *
	 */
	public function run($args)
	{
		//Fecha actual
		$present=date('Y-m-d');
		// restandole la cantidad de dias (15)
		$present=DateManagement::calculateDate('-15',$present);
		// echo $present."<br>";
		$rows=BalanceTime::model()->deleteAll("date_balance_time='".$present."' ");
		// echo "-> ".$rows;
	}
}
?>