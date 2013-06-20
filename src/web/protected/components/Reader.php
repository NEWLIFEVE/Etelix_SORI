<?php
class Reader
{
	public static function diario($ruta,$vencom,$tipo)
	{
		//Aumento el tiempo de ejecucion
		ini_set('max_execution_time', 1200);
		//Aumento la cantidad de memoria 
		ini_set('memory_limit', '256M');
		//importo la extension
		Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
        if(file_exists($ruta))
        {
        	$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('UTF-8');
			$data->read($ruta);
        }
		error_reporting(E_ALL ^ E_NOTICE);
		$date_balance=Utility::formatDate($data->sheets[0]['cells'][1][3]);
		$fecha = date('Y-m-j');
		$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		if($nuevafecha == $date_balance)
		{
			$exitos=0;
			$fallas=0;
			for($i = 1; $i < $data->sheets[0]['numRows']; $i++)
			{
				$model=new Balance;
 				$model->date_balance=$date_balance;
 				$model->type=$vencom;
 				$total=true;
 				$nuevo=true;
 				if($i>=5)
 				{
 					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
					{
						//Obtengo el id del destino
 						if($j==1)
 						{
 							if($tipo=="external")
 							{
 								$id_destination=Destination::getId($data->sheets[0]['cells'][$i][$j]);
 								$id_destination_int=NULL;
 								if($data->sheets[0]['cells'][$i][$j]=='Total')
 								{
 									break;
 								}
 							}
 							else
 							{
 								$id_destination_int=DestinationInt::getId($data->sheets[0]['cells'][$i][$j]);
 								$id_destination=NULL;
 								if($data->sheets[0]['cells'][$i][$j]=='Total')
 								{
 									break;
 								}
 							}
 							
 						}
 						elseif($j==2)
 						{
 							//obtengo el id del carrier
 							$id_carrier=Carrier::getId($data->sheets[0]['cells'][$i][$j]);
 							if($data->sheets[0]['cells'][$i][$j]=='Total')
 							{
 								$total=false;
 							}
 						}
 						elseif($j==3)
 						{
 							//minutos
 							$minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==4)
 						{
 							//ACD
 							$acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==5)
 						{
 							//ASR
							$asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==6)
 						{
 							//Margin %
							$margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==7)
 						{
 							//Margin per Min
 							$margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==8)
 						{
 							//Cost per Min
 							$cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==9)
 						{
 							//Revenue per Min
 							$revenue_per_min=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==10)
 						{
 							//PDD
 							$pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==11)
 						{
 							//Imcomplete Calls
 							$incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==12)
 						{
 							//Imcomplete Calls Ner
 							$incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==13)
 						{
 							//Complete Calls Ner
 							$complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==14)
 						{
 							//Complete Calls
 							$complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==15)
 						{
 							//Calls Attempts
 							$calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==16)
 						{
 							//Duration Real
 							$duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==17)
 						{
 							//Duration Cost
 							$duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==18)
 						{
 							//NER02 Efficient
 							$ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==19)
 						{
 							//NER02 Seizure
 							$ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==20)
 						{
 							//PDDCalls
 							$pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==21)
 						{
 							//Revenue
 							$revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==22)
 						{
 							//Cost
 							$cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==23)
 						{
 							//Margin
 							$margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						else
 						{
 							if($total)
 							{
 								$cant=$model->count('date_balance=:fecha AND id_carrier=:carrier AND id_destination=:destino',array(':fecha'=>$date_balance, ':carrier'=>$id_carrier, ':destino'=>$id_destination));
 								if($cant>0)
 								{
 									$model=Balance::model()->find('date_balance=:fecha AND id_carrier=:carrier AND id_destination=:destino',array(':fecha'=>$date_balance, ':carrier'=>$id_carrier, ':destino'=>$id_destination));
 									$model->date_change=date("Y-m-d");
 									$model->minutes=$minutes;
 									$model->acd=$acd;
 									$model->asr=$asr;
 									$model->margin_percentage=$margin_percentage;
 									$model->margin_per_minute=$margin_per_minute;
 									$model->cost_per_minute=$cost_per_minute;
 									$model->revenue_per_min=$revenue_per_min;
 									$model->pdd=$pdd;
 									$model->incomplete_calls=$incomplete_calls;
 									$model->incomplete_calls_ner=$incomplete_calls_ner;
 									$model->complete_calls_ner=$complete_calls_ner;
 									$model->complete_calls=$complete_calls;
 									$model->calls_attempts=$calls_attempts;
 									$model->duration_real=$duration_real;
 									$model->duration_cost=$duration_cost;
 									$model->ner02_efficient=$ner02_efficient;
 									$model->ner02_seizure=$ner02_seizure;
 									$model->pdd_calls=$pdd_calls;
 									$model->revenue=$revenue;
 									$model->cost=$cost;
 									$model->margin=$margin;
 									$model->saveAttributes(array('date_change','minutes','acd','asr','margin_percentage','margin_per_minute','cost_per_minute','revenue_per_min','pdd','incomplete_calls','incomplete_calls_ner','complete_calls_ner','complete_calls','calls_attempts','duration_real','duration_cost','ner02_efficient','ner02_seizure','pdd_calls','revenue','cost','margin'));
 								}
 								else
 								{
 									$model->date_balance=$date_balance;
 									$model->minutes=$minutes;
 									$model->acd=$acd;
 									$model->asr=$asr;
 									$model->margin_percentage=$margin_percentage;
 									$model->margin_per_minute=$margin_per_minute;
 									$model->cost_per_minute=$cost_per_minute;
 									$model->revenue_per_min=$revenue_per_min;
 									$model->pdd=$pdd;
 									$model->incomplete_calls=$incomplete_calls;
 									$model->incomplete_calls_ner=$incomplete_calls_ner;
 									$model->complete_calls_ner=$complete_calls_ner;
 									$model->complete_calls=$complete_calls;
 									$model->calls_attempts=$calls_attempts;
 									$model->duration_real=$duration_real;
 									$model->duration_cost=$duration_cost;
 									$model->ner02_efficient=$ner02_efficient;
 									$model->ner02_seizure=$ner02_seizure;
 									$model->pdd_calls=$pdd_calls;
 									$model->revenue=$revenue;
 									$model->cost=$cost;
 									$model->margin=$margin;
 									$model->id_carrier=$id_carrier;
 									$model->id_destination=$id_destination;
 									$model->id_destination_int=$id_destination_int;
 									if($model->save())
 									{
 										$exitos=$exitos+1;
 										$model->unsetAttributes();
 									}
 									else
 									{
 										$fallas=$fallas+1;
 									}
	 							}
 							}
 							return $texto="Numero de exitos: ".$exitos." y fallas: ".$fallas;
 						}
					}
 				}
			}
		}
		else
		{
			return false;
		}
	}
	public static function nombre($nombre)
	{
		if(stripos($nombre,"internal"))
		{
			if(stripos($nombre,"compra"))
    		{
    			$nuevoNombre="CompraInternal";
    		}
    		else
    		{
    			$nuevoNombre="VentaInternal";
    		}
    	}
    	else
    	{
    		if(stripos($nombre,"compra"))
    		{
    			$nuevoNombre="CompraExternal";
    		}
    		else
    		{
    			$nuevoNombre="VentaExternal";
    		}
    	}
    	return $nuevoNombre;
	}
	public static function tipo($nombre)
	{
		if(stripos($nombre,"internal"))
		{
			$tipo="internal";
    	}
    	else
    	{
			$tipo="external";
    	}
    	return $tipo;
	}
	public static function vencom($nombre)
	{
		if(stripos($nombre,"Compra"))
		{
			$tipo="0";
    	}
    	else
    	{
			$tipo="1";
    	}
    	return $tipo;
	}
}
?>