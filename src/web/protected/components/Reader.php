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
    public $horas;
    //errores de log
    const ERROR_SAVE_LOG=6;
    //errores guardando en base de datos
    const ERROR_SAVE_DB=5;
    //el archivo no esta en el servidor
	const ERROR_FILE=4;
	//la fecha del archivo es incorrecta
	const ERROR_DATE=3;
    //Ya esta registrado en el log
    const ERROR_EXISTS=2;
    // Error de estructura del archivo
    const ERROR_ESTRUC=1;
    //No hay errores
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
                            $model->status=1;
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
	public function hora($ruta,$nombreLog)
	{
		//Aumento la cantidad de memoria 
		ini_set('memory_limit', '512M');
		//importo la extension
		Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
		error_reporting(E_ALL ^ E_NOTICE);
        /**
        * Verifico si el archivo existe en el servidor
        */
		if(file_exists($ruta))
        {
        	$data = new Spreadsheet_Excel_Reader();
            /**
            * se pasa primero a la codificacion de ISO-8859-1 porque ya que dio problemas usando utf-8 directamente
            * pero al pasar los datos del nombre del carrier al modelo se convierten a utf-8
            */
			$data->setOutputEncoding('ISO-8859-1');
			$data->read($ruta);
        }
        else
        {
        	$this->error=self::ERROR_FILE;
			return false;
        }
        /**
        * Verifico que la fecha del archivo sea correcta
        */
        $date_balance=Utility::formatDate($data->sheets[0]['cells'][1][4]);
        $fecha=date('Y-m-d');
        if($fecha!=$date_balance)
        {
            $this->error=self::ERROR_DATE;
            return false;
        }
        /**
        * Valido que no este en el log
        */
        $numRows=$data->sheets[0]['numRows'];
        $numRows=$numRows-1;
        $this->horas=$data->sheets[0]['cells'][$numRows][1];
        for($i=$this->horas; $i <= 23 ; $i++)
        { 
            if(Log::existe(LogAction::getLikeId($nombreLog."%".$i."%")))
            {
                $this->error=self::ERROR_EXISTS;
                return false;
            }
        }
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
        //Cuantos segundos
        $regAprox=1500*$data->sheets[0]['cells'][$numRows][1];
        $segundos=$regAprox/2.8;
        $segundos=substr($segundos,0,4);
        //Aumento el tiempo de ejecucion
        ini_set('max_execution_time', $segundos);
        /**
        * Comienzo a leer el archivo
        */
        for($i=5;$i<$data->sheets[0]['numRows'];$i++)
        {
            for($j=1;$j<=$data->sheets[0]['numCols'];$j++)
            {
                switch($j)
                {
                    case 1:
                        //Obtengo la hora del registro
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total es que se termino el archivo
                            break 3;
                        }
                        else
                        {
                            $time=$data->sheets[0]['cells'][$i][$j];
                        }
                        break;
                    case 2:
                        //Obtengo el nombre del destino
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo voy a guardar en base de datos
                            break 2;
                        }
                        else
                        {
                            $name_destination=$data->sheets[0]['cells'][$i][$j];
                        }
                        break;
                    case 3:
                        //Obtengo el nombre del carrier
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo voy a guardar en base de datos
                            break 2;
                        }
                        else
                        {
                            //Aqui encodeo el nombre del carrier a utf-8
                            $name_carrier=utf8_encode($data->sheets[0]['cells'][$i][$j]);
                        }
                        break;
                    case 4;
                        //minutos
                        $minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                    case 5;
                        //ACD
                        $acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 6;
                        //ASR
                        $asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7;
                        //Margin %
                        $margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8;
                        //Margin per Min
                        $margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9;
                        //Cost per Min
                        $cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10;
                        //Revenue per Min
                        $revenue_per_min=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11;
                        //PDD
                        $pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12;
                        //Imcomplete Calls
                        $incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13;
                        //Imcomplete Calls Ner
                        $incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14;
                        //Complete Calls Ner
                        $complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15;
                        //Complete Calls
                        $complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16;
                        //Calls Attempts
                        $calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17;
                        //Duration Real
                        $duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18;
                        //Duration Cost
                        $duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19;
                        //NER02 Efficient
                        $ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20;
                        //NER02 Seizure
                        $ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21;
                        //PDDCalls
                        $pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22;
                        //Revenue
                        $revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23;
                        //Cost
                        $cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 24;
                        //Margin
                        $margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    default:
                        /**
                        * luego de tener la fila completa la grabo en base de datos
                        */
                        //primero reviso si existe en base de datos
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
        $this->error=self::ERROR_NONE;
        return true;
	}

    /*
    * Funcion de carga de archivos de rerate
    */
    public function rerate($ruta,$accionLog)
    {
        //Aumento el tiempo de ejecucion
        ini_set('max_execution_time', 1200);
        //Aumento la cantidad de memoria
        ini_set('memory_limit', '256M');
        //importo la extension
        Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
        //Oculto los errores
        error_reporting(E_ALL ^ E_NOTICE);
        //instancio la clase de lector
        //Verifico la existencia del archivo
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
        //Comienza la lectura
        for($i=5; $i<$data->sheets[0]['numRows']; $i++)
        {
            $balancetemp=new BalanceTemp;
            //Obtengo la fecha
            $balancetemp->date_balance=Utility::formatDate($data->sheets[0]['cells'][1][3]);
            for($j=1; $j<=$data->sheets[0]['numCols']; $j++)
            { 
                switch($j)
                {
                    case 1:
                        //Obtengo el id del destino
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            break 3;
                        }
                        if($this->tipo=="external")
                        {
                            $balancetemp->id_destination=Destination::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
                            $balancetemp->id_destination_int=NULL;
                        }
                        else
                        {
                            $balancetemp->id_destination_int=DestinationInt::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
                            $balancetemp->id_destination=NULL;
                        }
                        break;
                    case 2:
                        //obtengo el id del carrier y antes lo codifico a utf-8 para no dar problemas con la funcion
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //Si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $balancetemp->id_carrier=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 3:
                        //minutos
                        $balancetemp->minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 4:
                        //ACD
                        $balancetemp->acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 5:
                        //ASR
                        $balancetemp->asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 6:
                        //Margin %
                        $balancetemp->margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7:
                        //Margin per Min
                        $balancetemp->margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8:
                        //Cost per Min
                        $balancetemp->cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9:
                        //Revenue per Min
                        $balancetemp->revenue_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10:
                        //PDD
                        $balancetemp->pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11:
                        //Imcomplete Calls
                        $balancetemp->incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12:
                        //Imcomplete Calls Ner
                        $balancetemp->incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13:
                        //Complete Calls Ner
                        $balancetemp->complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14:
                        //Complete Calls
                        $balancetemp->complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15:
                        //Calls Attempts
                        $balancetemp->calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16:
                        //Duration Real
                        $balancetemp->duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17:
                        //Duration Cost
                        $balancetemp->duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18:
                        //NER02 Efficient
                        $balancetemp->ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19:
                        //NER02 Seizure
                        $balancetemp->ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20:
                        //PDDCalls
                        $balancetemp->pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21:
                        //Revenue
                        $balancetemp->revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22:
                        //Cost
                        $balancetemp->cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23:
                        //Margin
                        $balancetemp->margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    default:
                        $balancetemp->date_change=date("Y-m-d");
                        $balancetemp->type=$this->vencom;
                        if($balancetemp->save())
                        {
                            $this->error=ERROR_NONE;
                        }
                        else
                        {
                            $this->error=ERROR_SAVE_DB;
                        }
                }
            }
        }
        if($this->error>0)
        {
            return false;
        }
        else
        {
            if(Log::registrarLog(LogAction::getId($accionLog),$balancetemp->date_balance))
            {
                $this->error=ERROR_NONE;
                return true;
            }
            else
            {
                $this->error=ERROR_SAVE_LOG;
                return false;
            }
            
        }                   
    }
    /**
    * Esta funcion se encarga de definir que nombre darle al archivo al momento de guardarlo en el servidor
    */
	public static function nombre($nombre)
    {
        $primero="Compra";
        $segundo="External";
        $tercero="";
        if(stripos($nombre,"internal"))
        {
            $segundo="Internal";
        }
        if(stripos($nombre,"enta"))
        {
            $primero="Venta";
        }
        if(stripos($nombre,'rerate') || stripos($nombre, "RR"))
        {
            $tercero="RR";
        }
        if(stripos($nombre,'GMT'))
        {
            $tercero="Hora";
        }
        $nuevoNombre=$primero.$segundo.$tercero;
        return $nuevoNombre;     
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