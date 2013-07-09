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
	const ERROR_ESTRUC=4;
	const ERROR_FILE=3;
	const ERROR_EXISTS=2;
	const ERROR_DATE=1;
	const ERROR_NONE=0;

	/*
	* Funcion de carga de archivos diarios
	*/
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
		$fecha = date('Y-m-d');
		$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
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

	/*
	* Funcion de carga de archivos hora
	*/
	public function hora($ruta,$validador)
	{
		//Aumento el tiempo de ejecucion
		ini_set('max_execution_time', 1200);
		//Aumento la cantidad de memoria 
		ini_set('memory_limit', '256M');
		//importo la extension
		Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
		error_reporting(E_ALL ^ E_NOTICE);
		//Verifico si el archivo existe en el servidor
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
        $date_balance=Utility::formatDate($data->sheets[0]['cells'][1][4]);
        $fecha=date('Y-m-d');
        //Verifico si la fecha es correcta
        if($fecha == $date_balance)
        {
        	/**
        	* Valido la estructura de horas
        	*/
        	$actual=-1;
        	for ($i=0; $i<$data->sheets[0]['numRows']; $i++)
        	{ 
        		if($data->sheets[0]['cells'][$i][1]!="Total" && $data->sheets[0]['cells'][$i][1]!="Date" && $data->sheets[0]['cells'][$i][1]!="Hour")
        		{
        			//Verifico que sean secuenciales las horas
        			if($actual <= $data->sheets[0]['cells'][$i][1])
        			{
  						if($actual==$data->sheets[0]['cells'][$i][1]-1)
        				{
        					$actual=$data->sheets[0]['cells'][$i][1];
        				}
        				elseif($actual==$data->sheets[0]['cells'][$i][1])
        				{
        					$actual=$actual;
        				}
        				else
        				{
        					$this->error=self::ERROR_ESTRUC;
        					return false;
        				}
        			}
        		}
        	}
        	//Valido que la mayor hora sea igual al nombre del archivo
        	if($actual<>$validador)
        	{
        		$this->error=self::ERROR_ESTRUC;
        		return false;
        	}
        	/**
        	* Comienzo a leer el archivo
        	*/
        	for($i=5;$i<$data->sheets[0]['numRows'];$i++)
        	{
        		$total=true;
				for($j=1;$j<=$data->sheets[0]['numCols'];$j++)
				{
					if($j==1)
 					{
 						//Obtengo la hora del registro
 						if($data->sheets[0]['cells'][$i][$j]=='Total')
 						{
 							//si es total es que se termino el archivo
 							break 2;
 						}
 						else
 						{
 							$time=$data->sheets[0]['cells'][$i][$j];
 						}
 					}
 					elseif($j==2)
 					{
 						//Obtengo el nombre del destino
 						if($data->sheets[0]['cells'][$i][$j]=='Total')
 						{
 							//no lo voy a guardar en base de datos
 							$total=false;
 						}
 						else
 						{
 							$name_destination=$data->sheets[0]['cells'][$i][$j];
 						}
 					}
 					elseif($j==3)
 					{
 						//Obtengo el nombre del carrier
 						if($data->sheets[0]['cells'][$i][$j]=='Total')
 						{
 							//no lo voy a guardar en base de datos
 							$total=false;
 						}
 						else
 						{
 							$name_carrier=utf8_encode($data->sheets[0]['cells'][$i][$j]);
 						}
 					}
 					elseif($j==4)
 					{
 						//minutos
 						$minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==5)
 					{
 						//ACD
 						$acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==6)
 					{
 						//ASR
						$asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==7)
 					{
 						//Margin %
						$margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==8)
 					{
 						//Margin per Min
 						$margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==9)
 					{
 						//Cost per Min
 						$cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==10)
 					{
 						//Revenue per Min
 						$revenue_per_min=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==11)
 					{
 						//PDD
 						$pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==12)
 					{
 						//Imcomplete Calls
 						$incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==13)
 					{
 						//Imcomplete Calls Ner
 						$incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==14)
 					{
 						//Complete Calls Ner
 						$complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==15)
 					{
 						//Complete Calls
 						$complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==16)
 					{
 						//Calls Attempts
 						$calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==17)
 					{
 						//Duration Real
 						$duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==18)
 					{
 						//Duration Cost
 						$duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==19)
 					{
 						//NER02 Efficient
 						$ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==20)
 					{
 						//NER02 Seizure
 						$ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==21)
 					{
 						//PDDCalls
 						$pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==22)
 					{
 						//Revenue
 						$revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==23)
 					{
 						//Cost
 						$cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					elseif($j==24)
 					{
 						//Margin
 						$margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 					}
 					else
 					{
 						if($total)
 						{
 							$model=BalanceTime::model()->find('time=:time AND date_balance_time=:date AND type=:tipo AND name_carrier=:carrier AND name_destination=:destination',array(':time'=>$time,':date'=>$date_balance,':tipo'=>$this->vencom,':carrier'=>$name_carrier,':destination'=>$name_destination));
 							if($model!=null)
 							{
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
 								$model->date_change=date("Y-m-d");
 								$model->time_change=date("H:i:s");
 								if($model->save())
 								{
 									$this->nuevos=$this->nuevos+1;
 									$model->unsetAttributes();
 								}
 								else
 								{
 									$this->fallas=$this->fallas+1;
 								}
 							}
 							else
 							{
 								$model=new BalanceTime;
 								$model->date_balance_time=$date_balance;
 								$model->time=$time;
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
 								$model->date_change=date("Y-m-d");
 								$model->type=$this->vencom;
 								$model->time_change=date("H:i:s");
 								$model->name_carrier=$name_carrier;
 								$model->name_destination=$name_destination;
 								if($model->save())
 								{
 									$this->actualizados=$this->actualizados+1;
 									$model->unsetAttributes();
 								}
 								else
 								{
 									$this->fallas=$this->fallas+1;
 								}
 							}
 						}
 					}
				}
        	}
        	$this->error=self::ERROR_NONE;
			return true;
        }
        else
		{
			$this->error=self::ERROR_DATE;
			return false;
		}
	}
    /**
    * esta funcion se encarga de definir que nombre darle al archivo al momento de guardarlo en el servidor
    */
	public static function nombre($nombre)
    {
        //primero obtengo el numero de la frase GMT
        $posicion=strpos($nombre,'GMT');
        if($posicion)
        {
            if(substr($nombre, strpos($nombre,'GMT')-2, 1)==" ")
            {
                $valor=substr($nombre, strpos($nombre,'GMT')-1, 1);
            }
            else
            {
                $valor=substr($nombre, strpos($nombre,'GMT')-2, 2);
            }
        }
        else
        {
            $valor=false;
        }
        if(stripos($nombre,"internal"))
        {
            if(stripos($nombre,"compra"))
            {
                $nuevoNombre="CompraInternal";
            }
            elseif(stripos($nombre,"venta"))
            {
                $nuevoNombre="VentaInternal";
            }
            else
            {
                $nuevoNombre=false;
            }
        }
        else
        {
            if(stripos($nombre,"compra"))
            {
                $nuevoNombre="CompraExternal";
            }
            elseif(stripos($nombre,"venta"))
            {
                $nuevoNombre="VentaExternal";
            }
            else
            {
                $nuevoNombre=false;
            }
        }
        if($valor)
        {
            return $nuevoNombre.$valor;
        }
        else
        {
            return $nuevoNombre;
        }
    }
    /**
    * Encargada de definir atributos para proceder a la lectura del archivo
    */
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
    		//Venta
    		$this->vencom=1;
    	}
    	else
    	{
    		//Compra
    		$this->vencom=0;
    	}
	}
}
?>