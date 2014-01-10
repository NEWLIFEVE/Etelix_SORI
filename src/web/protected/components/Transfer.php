<?php
/**
* 
*/
class Transfer extends CApplicationComponent
{
	/**
	 *
	 */
	public function init()
	{

	}

	/**
	 *
	 */
	public function run($date)
	{
		ini_set('memory_limit', '300M');
		ini_set('max_execution_time', 300);
		$local=Balance::model()->findAll('date_balance=:date',array(':date'=>$date));
		$sql="INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_carrier_supplier, id_destination, id_destination_int, status, id_carrier_customer)
			  VALUES ";

		$values="";
		$num=count($local);
		foreach ($local as $key => $balance)
		{
			if($balance->id_destination==null) $balance->id_destination="NULL";
			if($balance->id_destination_int==null) $balance->id_destination_int="NULL";
			$values.="(";
			$values.="'".$balance->date_balance."',";
			$values.=$balance->minutes.",";
			$values.=$balance->acd.",";
			$values.=$balance->asr.",";
			$values.=$balance->margin_percentage.",";
			$values.=$balance->margin_per_minute.",";
			$values.=$balance->cost_per_minute.",";
			$values.=$balance->revenue_per_minute.",";
			$values.=$balance->pdd.",";
			$values.=$balance->incomplete_calls.",";
			$values.=$balance->incomplete_calls_ner.",";
			$values.=$balance->complete_calls.",";
			$values.=$balance->complete_calls_ner.",";
			$values.=$balance->calls_attempts.",";
			$values.=$balance->duration_real.",";
			$values.=$balance->duration_cost.",";
			$values.=$balance->ner02_efficient.",";
			$values.=$balance->ner02_seizure.",";
			$values.=$balance->pdd_calls.",";
			$values.=$balance->revenue.",";
			$values.=$balance->cost.",";
			$values.=$balance->margin.",";
			$values.="'".$balance->date_change."',";
			$values.=$balance->id_carrier_supplier.",";
			$values.=$balance->id_destination.",";
			$values.=$balance->id_destination_int.",";
			$values.=$balance->status.",";
			$values.=$balance->id_carrier_customer;
			$values.=")";
			if($key<$num-1) $values.=",";
		}
		$sql.=$values;

		$command=Yii::app()->cloud->createCommand($sql);
        if($command->execute())
        {
        	echo "Exito fecha: ".$date."\n";
        }
	}
}

?>