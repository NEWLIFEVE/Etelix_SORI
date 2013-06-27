<?php
class Reader
{
	private $nuevos=0;
	private $actualizados=0;
	private $fallas=0;
	public $model;
	public $tipo;
	public $vencom;
	public $error;
	const ERROR_FILE=3;
	const ERROR_EXISTS=2;
	const ERROR_DATE=1;
	const ERROR_NONE=0;

	public function diario($ruta)
	{
		//Aumento el tiempo de ejecucion
		ini_set('max_execution_time', 1200);
		//Aumento la cantidad de memoria 
		ini_set('memory_limit', '256M');
		//importo la extension
		Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
		error_reporting(E_ALL ^ E_NOTICE);
		if(file_exists($ruta))
        {
        	$data = new Spreadsheet_Excel_Reader();
        	//uso esta codificacion ya que dio problemas usando utf-8 directamente
			$data->setOutputEncoding('ISO-8859-1');
			$data->read($ruta);
        }
        else
        {
        	$this->error=self::ERROR_FILE;
			return false;
        }
        //verifico que los archivos tengan la fecha correcta
		$date_balance=Utility::formatDate($data->sheets[0]['cells'][1][3]);
		$fecha = date('Y-m-j');
		$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		if($nuevafecha == $date_balance)
		{
			for($i=5;$i<$data->sheets[0]['numRows'];$i++)
			{
				$model=new Balance;
				$total=true;
 				$nuevo=true;
				for($j=1;$j<=$data->sheets[0]['numCols'];$j++)
				{
					//Obtengo el id del destino
 					if($j==1)
 					{
 						if($data->sheets[0]['cells'][$i][$j]=='Total')
 							{
 								break 2;
 							}
 						if($this->tipo=="external")
 						{
 							$model->id_destination=Destination::getId($data->sheets[0]['cells'][$i][$j]);
 							$model->id_destination_int=NULL;
 						}
 						else
 						{
 							$model->id_destination_int=DestinationInt::getId($data->sheets[0]['cells'][$i][$j]);
 							$model->id_destination=NULL;
 							
 						}
 					}
 					elseif($j==2)
 					{
 						//obtengo el id del carrier y antes lo codifico a utf-8 para no dar problemas con la funcion
 						$model->id_carrier=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
 						if($data->sheets[0]['cells'][$i][$j]=='Total')
 						{
 							$total=false;
 						}
 					}
 					elseif($j==3)
 					{
 						//minutos
 						$model->minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==4)
 					{
 						//ACD
 						$model->acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==5)
 					{
 						//ASR
						$model->asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==6)
 					{
 						//Margin %
						$model->margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==7)
 					{
 						//Margin per Min
 						$model->margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==8)
 					{
 						//Cost per Min
 						$model->cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==9)
 					{
 						//Revenue per Min
 						$model->revenue_per_min=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==10)
 					{
 						//PDD
 						$model->pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==11)
 					{
 						//Imcomplete Calls
 						$model->incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==12)
 					{
 						//Imcomplete Calls Ner
 						$model->incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==13)
 					{
 						//Complete Calls Ner
 						$model->complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==14)
 					{
 						//Complete Calls
 						$model->complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==15)
 					{
 						//Calls Attempts
 						$model->calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==16)
 					{
 						//Duration Real
 						$model->duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==17)
 					{
 						//Duration Cost
 						$model->duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==18)
 					{
 						//NER02 Efficient
 						$model->ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==19)
 					{
 						//NER02 Seizure
 						$model->ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==20)
 					{
 						//PDDCalls
 						$model->pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==21)
 					{
 						//Revenue
 						$model->revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==22)
 					{
 						//Cost
 						$model->cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==23)
 					{
 						//Margin
 						$model->margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					else
 					{
 						if($total)
 						{
 							$model->date_change=date("Y-m-d");
 							$model->type=$this->vencom;
 							$model->date_balance=$date_balance;
 							if($model->save())
 							{
 								$this->nuevos=$this->nuevos+1;
 								$model->unsetAttributes();
 							}
 							else
 							{
 								$this->fallas=$this->fallas+1;
 							}
 						}//fin de if de total
 					}//fin del multiple if
				}//fin de for de $j
			}//fin de for de $i
			$this->error=self::ERROR_NONE;
			return true;
		}
		else
		{
			$this->error=self::ERROR_DATE;
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
	public function define($nombre)
	{
		if(stripos($nombre,"internal"))
		{
			$this->tipo="internal";
    	}
    	else
    	{
    		$this->tipo="external";
    	}
    	if(stripos($nombre,"enta"))
    	{
    		$this->vencom=1;
    	}
    	else
    	{
    		$this->vencom=0;
    	}
	}
}
?>