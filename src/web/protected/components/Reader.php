<?php
/**
 * Archivo de clase Reader.
 * @version 6.1
 * @author Manuel Zambrano <mmzmm3z@gmail.com>
 * @copyright 2013 Sacet Todos los derechos reservados.
 * @package components
 */
class Reader
{
    public $excel;
    public $valida;
    public $error=0;
    public $errorComment;
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

    /**
    * Funcion de carga de archivos diarios
    * @param string $ruta: ruta absoluta de archivo que va a ser leido
    * @return boolean
    */
    public function diario($ruta,$fecha_diario,$nombre)
    {
    	$this->valida=new ValidationsArchCapt;
    	$values='';
        //aumento el tiempo maximo de ejecucion
        ini_set('max_execution_time', 1200);
        $this->valida->carga($ruta);
        
        for($i=5;$i<=$this->valida->excel->sheets[0]['numRows'];$i++)
        {
            $valores=array();
            for($j=1;$j<=$this->valida->excel->sheets[0]['numCols'];$j++)
            {
                switch($j)
                {
                    case 1:
                        if($this->valida->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total es que ya se termino el archivo
                            break 3;
                        }
                        else
                        {
                        	  $this->valida->define($nombre);
                            if($this->valida->tipo=="external")
                            {
                                //Obtengo el id de destino externo
                                $valores['id_destination']=Destination::getId(utf8_encode($this->valida->excel->sheets[0]['cells'][$i][$j]));
                                $valores['id_destination_int']='NULL';
                                $id_destination_int=$valores['id_destination_int'];
                                $id_destination=$valores['id_destination'];
                            }
                            elseif($this->valida->tipo=="internal")
                            {
                                //obtengo el id del destino interno
                                $valores['id_destination_int']=DestinationInt::getId(utf8_encode($this->valida->excel->sheets[0]['cells'][$i][$j]));
                                $valores['id_destination']='NULL';
                                $id_destination_int=$valores['id_destination_int'];
                                $id_destination=$valores['id_destination'];
                            }
                        }
                        break;
                    case 2:
                        if($this->valida->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $valores['id_carrier_customer']=Carrier::getId(utf8_encode($this->valida->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 3:
                        if($this->valida->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //Si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $valores['id_carrier_supplier']=Carrier::getId(utf8_encode($this->valida->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 4:
                        //minutos
                        $valores['minutes']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 5:
                        //ACD
                        $valores['acd']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 6:
                        //ASR
                        $valores['asr']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7:
                        //Margin %
                        $valores['margin_percentage']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8:
                        //Margin per Min
                        $valores['margin_per_minute']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9:
                        //Cost per Min
                        $valores['cost_per_minute']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10:
                        //Revenue per Min
                        $valores['revenue_per_minute']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11:
                        //PDD
                        $valores['pdd']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12:
                        //Imcomplete Calls
                        $valores['incomplete_calls']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13:
                        //Imcomplete Calls Ner
                        $valores['incomplete_calls_ner']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14:
                        //Complete Calls Ner
                        $valores['complete_calls_ner']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15:
                        //Complete Calls
                        $valores['complete_calls']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16:
                        //Calls Attempts
                        $valores['calls_attempts']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17:
                        //Duration Real
                        $valores['duration_real']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18:
                        //Duration Cost
                        $valores['duration_cost']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19:
                        //NER02 Efficient
                        $valores['ner02_efficient']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20:
                        //NER02 Seizure
                        $valores['ner02_seizure']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21:
                        //PDDCalls
                        $valores['pdd_calls']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22:
                        //Revenue
                        $valores['revenue']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23:
                        //Cost
                        $valores['cost']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 24:
                        //Margin
                        $valores['margin']=Utility::notNull($this->valida->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 25:
                    	$values.="(";
                        $values.="'".$fecha_diario."',";
                        $values.=$valores['minutes'].",";
                        $values.=$valores['acd'].",";
                        $values.=$valores['asr'].",";
                        $values.=$valores['margin_percentage'].",";
                        $values.=$valores['margin_per_minute'].",";
                        $values.=$valores['cost_per_minute'].",";
                        $values.=$valores['revenue_per_minute'].",";
                        $values.=$valores['pdd'].",";
                        $values.=$valores['incomplete_calls'].",";
                        $values.=$valores['incomplete_calls_ner'].",";
                        $values.=$valores['complete_calls'].",";
                        $values.=$valores['complete_calls_ner'].",";
                        $values.=$valores['calls_attempts'].",";
                        $values.=$valores['duration_real'].",";
                        $values.=$valores['duration_cost'].",";
                        $values.=$valores['ner02_efficient'].",";
                        $values.=$valores['ner02_seizure'].",";
                        $values.=$valores['pdd_calls'].",";
                        $values.=$valores['revenue'].",";
                        $values.=$valores['cost'].",";
                        $values.=$valores['margin'].",";
                        $values.="'".date('Y-m-d')."',";
                        $values.=$valores['id_carrier_supplier'].",";
                        $values.=$valores['id_destination'].",";
                        $values.=$valores['id_destination_int'].",";
                        $values.="1,";
                        $values.=$valores['id_carrier_customer'].")";
                        break;
                }
            }//fin de for de $j
            if($i<$this->valida->excel->sheets[0]['numRows'])
            {
                $values.=",";
            }
        }//fin de for de $i
        
    	// busco y lleno un array con los datos que estan en bd antes de eliminrlos
    	//le mando $id_destination,$id_destination_int para saber cual se esta guardando si internal o external
    	 $string_data_preliminar=$this->valida->load_arch_temp($fecha_diario,$id_destination,$id_destination_int);

			$sql="INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_carrier_supplier, id_destination, id_destination_int, status, id_carrier_customer) VALUES ".$values;
			$command = Yii::app()->db->createCommand($sql);
			    
			if($command->execute())
	        {
	            $this->valida->error=self::ERROR_NONE;
	            // si el string viene vacio no elmino nada, es la primera carga de interna o externa 
	            if($string_data_preliminar!="")
	            {
	             $this->valida->delete_arch_temp($string_data_preliminar,$id_destination,$id_destination_int);
				}
    	        return true;
	        }
	        else
	        {
	            $this->valida->error=self::ERROR_SAVE_DB;
	            return false;
	        }
    }

    /**
    * Funcion de carga de archivos hora
    * @param string $ruta: ruta absoluta del archivo que va a ser leido
    * @return boolean
    */
//    public function hora($ruta)
//    {
//        //importo la extension
//        Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
//        error_reporting(E_ALL ^ E_NOTICE);
//        /**
//        * Verifico si el archivo existe en el servidor
//        */
//        if(file_exists($ruta))
//        {
//            $data = new Spreadsheet_Excel_Reader();
//            /**
//            * se pasa primero a la codificacion de ISO-8859-1 porque ya que dio problemas usando utf-8 directamente
//            * pero al pasar los datos del nombre del carrier al modelo se convierten a utf-8
//            */
//            $data->setOutputEncoding('ISO-8859-1');
//            $data->read($ruta);
//        }
//        else
//        {
//            $this->error=self::ERROR_FILE;
//            return false;
//        }
//        /**
//        * Verifico que la fecha del archivo sea correcta
//        */
//        $date_balance_time=Utility::formatDate($data->sheets[0]['cells'][1][5]);
//        $fecha=date('Y-m-d');
//        if($fecha!=$date_balance_time)
//        {
//            $this->error=self::ERROR_DATE;
//            return false;
//        }
//        /**
//        * Valido que no este en el log
//        */
//        $numRows=$data->sheets[0]['numRows'];
//        $numRows=$numRows-1;
//        $this->horas=$data->sheets[0]['cells'][$numRows][1];
//        for($i=$this->horas; $i <= 23 ; $i++)
//        { 
//            if(Log::existe(LogAction::getId("Carga Ruta Internal ".$i."GMT")))
//            {
//                $this->error=self::ERROR_EXISTS;
//                return false;
//            }
//        }
//        /**
//        * Valido la estructura de horas
//        */
//        $actual=0;
//        $contador=0;
//        for ($i=5; $i<$data->sheets[0]['numRows']; $i++)
//        { 
//            if($data->sheets[0]['cells'][$i][1]!="Total" && $data->sheets[0]['cells'][$i][1]!="Date" && $data->sheets[0]['cells'][$i][1]!="Hour")
//            {
//                //Verifico que sean secuenciales las horas
//                if($actual <= $data->sheets[0]['cells'][$i][1])
//                {
//                    if($actual==$data->sheets[0]['cells'][$i][1])
//                    {
//                        $contador=$contador+1;
//                    }
//                    elseif($actual==$data->sheets[0]['cells'][$i][1]-1)
//                    {
//                        if($contador<=1)
//                        {
//                            $this->error=self::ERROR_ESTRUC;
//                            return false;
//                        }
//                        else
//                        {
//                            $contador=0;
//                            $actual=$data->sheets[0]['cells'][$i][1];
//                        }
//                    }
//                    else
//                    {
//                        $this->error=self::ERROR_ESTRUC;
//                        return false; 
//                    }
//                }
//            }
//        }
//        //Cuantos segundos
//        $regAprox=1500*$data->sheets[0]['cells'][$numRows][1];
//        $segundos=$regAprox/2.8;
//        $segundos=substr($segundos,0,4);
//        //Aumento el tiempo de ejecucion
//        ini_set('max_execution_time', $segundos);
//        /**
//        * Comienzo a leer el archivo
//        */
//        for($i=5;$i<$data->sheets[0]['numRows'];$i++)
//        {
//            for($j=1;$j<=$data->sheets[0]['numCols'];$j++)
//            {
//                switch($j)
//                {
//                    case 1:
//                        //Obtengo la hora del registro
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //si es total es que se termino el archivo
//                            break 3;
//                        }
//                        else
//                        {
//                            $time=$data->sheets[0]['cells'][$i][$j];
//                        }
//                        break;
//                    case 2:
//                        //Obtengo el nombre del destino
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //si es total no lo voy a guardar en base de datos
//                            break 2;
//                        }
//                        else
//                        {
//                            $name_destination=utf8_encode($data->sheets[0]['cells'][$i][$j]);
//                        }
//                        break;
//                    case 3:
//                        //Obtengo el nombre del customer
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //si es total no lo voy a guardar en base de datos
//                            break 2;
//                        }
//                        else
//                        {
//                            //Aqui encodeo el nombre del carrier a utf-8
//                            $name_customer=utf8_encode($data->sheets[0]['cells'][$i][$j]);
//                        }
//                        break;
//                    case 4:
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //si es total no lo voy guardar en base de datos
//                            break 2;
//                        }
//                        else
//                        {
//                            $name_supplier=utf8_encode($data->sheets[0]['cells'][$i][$j]);
//                        }
//                        break;
//                    case 5;
//                        //minutos
//                        $minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                    case 6;
//                        //ACD
//                        $acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 7;
//                        //ASR
//                        $asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 8;
//                        //Margin %
//                        $margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 9;
//                        //Margin per Min
//                        $margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 10;
//                        //Cost per Min
//                        $cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 11;
//                        //Revenue per Min
//                        $revenue_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 12;
//                        //PDD
//                        $pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 13;
//                        //Imcomplete Calls
//                        $incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 14;
//                        //Imcomplete Calls Ner
//                        $incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 15;
//                        //Complete Calls Ner
//                        $complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 16;
//                        //Complete Calls
//                        $complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 17;
//                        //Calls Attempts
//                        $calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 18;
//                        //Duration Real
//                        $duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 19;
//                        //Duration Cost
//                        $duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 20;
//                        //NER02 Efficient
//                        $ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 21;
//                        //NER02 Seizure
//                        $ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 22;
//                        //PDDCalls
//                        $pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 23;
//                        //Revenue
//                        $revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 24;
//                        //Cost
//                        $cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 25;
//                        //Margin
//                        $margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    default:
//                        /**
//                        * luego de tener la fila completa la grabo en base de datos
//                        */
//                        //primero reviso si existe en base de datos
//                        $model=BalanceTime::model()->find('time=:time AND date_balance_time=:date AND name_customer=:customer AND name_supplier=:supplier AND name_destination=:destination',array(':time'=>$time,':date'=>$date_balance_time,':customer'=>$name_customer, ':supplier'=>$name_supplier, ':destination'=>$name_destination));
//                        if($model!=null)
//                        {
//                            $model->minutes=$minutes;
//                            $model->acd=$acd;
//                            $model->asr=$asr;
//                            $model->margin_percentage=$margin_percentage;
//                            $model->margin_per_minute=$margin_per_minute;
//                            $model->cost_per_minute=$cost_per_minute;
//                            $model->revenue_per_minute=$revenue_per_minute;
//                            $model->pdd=$pdd;
//                            $model->incomplete_calls=$incomplete_calls;
//                            $model->incomplete_calls_ner=$incomplete_calls_ner;
//                            $model->complete_calls_ner=$complete_calls_ner;
//                            $model->complete_calls=$complete_calls;
//                            $model->calls_attempts=$calls_attempts;
//                            $model->duration_real=$duration_real;
//                            $model->duration_cost=$duration_cost;
//                            $model->ner02_efficient=$ner02_efficient;
//                            $model->ner02_seizure=$ner02_seizure;
//                            $model->pdd_calls=$pdd_calls;
//                            $model->revenue=$revenue;
//                            $model->cost=$cost;
//                            $model->margin=$margin;
//                            $model->date_change=date("Y-m-d");
//                            $model->time_change=date("H:i:s");
//                            if($model->save())
//                            {
//                                $this->actualizados=$this->actualizados+1;
//                                $model->unsetAttributes();
//                            }
//                            else
//                            {
//                                $this->fallas=$this->fallas+1;
//                            }
//                        }
//                        else
//                        {
//                            $model=new BalanceTime;
//                            $model->date_balance_time=$date_balance_time;
//                            $model->time=$time;
//                            $model->minutes=$minutes;
//                            $model->acd=$acd;
//                            $model->asr=$asr;
//                            $model->margin_percentage=$margin_percentage;
//                            $model->margin_per_minute=$margin_per_minute;
//                            $model->cost_per_minute=$cost_per_minute;
//                            $model->revenue_per_minute=$revenue_per_minute;
//                            $model->pdd=$pdd;
//                            $model->incomplete_calls=$incomplete_calls;
//                            $model->incomplete_calls_ner=$incomplete_calls_ner;
//                            $model->complete_calls_ner=$complete_calls_ner;
//                            $model->complete_calls=$complete_calls;
//                            $model->calls_attempts=$calls_attempts;
//                            $model->duration_real=$duration_real;
//                            $model->duration_cost=$duration_cost;
//                            $model->ner02_efficient=$ner02_efficient;
//                            $model->ner02_seizure=$ner02_seizure;
//                            $model->pdd_calls=$pdd_calls;
//                            $model->revenue=$revenue;
//                            $model->cost=$cost;
//                            $model->margin=$margin;
//                            $model->date_change=date("Y-m-d");
//                            $model->time_change=date("H:i:s");
//                            $model->name_supplier=$name_supplier;
//                            $model->name_customer=$name_customer;
//                            $model->name_destination=$name_destination;
//                            if($model->save())
//                            {
//                                $this->nuevos=$this->nuevos+1;
//                                $model->unsetAttributes();
//                            }
//                            else
//                            {
//                                $this->fallas=$this->fallas+1;
//                            }
//                        }
//                }
//            }
//        }
//        $this->error=self::ERROR_NONE;
//        return true;
//    }

    /*
    * Funcion de carga de archivos de rerate
    */
//    public function rerate($ruta,$accionLog)
//    {
//        //Aumento el tiempo de ejecucion
//        //ini_set('max_execution_time', 1200);
//        //importo la extension
//        Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
//        //Oculto los errores
//        error_reporting(E_ALL ^ E_NOTICE);
//        //instancio la clase de lector
//        //Verifico la existencia del archivo
//        if(file_exists($ruta))
//        {
//            $data = new Spreadsheet_Excel_Reader();
//            //uso esta codificacion ya que dio problemas usando utf-8 directamente
//            $data->setOutputEncoding('ISO-8859-1');
//            $data->read($ruta);
//        }
//        else
//        {
//            $this->error=self::ERROR_FILE;
//            return false;
//        }
//        //Comienza la lectura
//        for($i=5; $i<$data->sheets[0]['numRows']; $i++)
//        {
//            $balancetemp=new BalanceTemp;
//            //Obtengo la fecha
//            $balancetemp->date_balance=Utility::formatDate($data->sheets[0]['cells'][1][4]);
//            for($j=1; $j<=$data->sheets[0]['numCols']; $j++)
//            { 
//                switch($j)
//                {
//                    case 1:
//                        //Obtengo el id del destino
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            break 3;
//                        }
//                        else
//                        {
//                           if($this->tipo=="external")
//                            {
//                                $balancetemp->id_destination=Destination::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
//                                $balancetemp->id_destination_int=NULL;
//                            }
//                            else
//                            {
//                                $balancetemp->id_destination_int=DestinationInt::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
//                                $balancetemp->id_destination=NULL;
//                            } 
//                        }
//                        break;
//                    case 2:
//                        //obtengo el id del carrier y antes lo codifico a utf-8 para no dar problemas con la funcion
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //Si es total no lo guardo en base de datos
//                            break 2;
//                        }
//                        else
//                        {
//                            $balancetemp->id_carrier_customer=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
//                        }
//                        break;
//                    case 3:
//                        //obtengo el id del carrier y antes lo codifico a utf-8 para no dar problemas con la funcion
//                        if($data->sheets[0]['cells'][$i][$j]=='Total')
//                        {
//                            //Si es total no lo guardo en base de datos
//                            break 2;
//                        }
//                        else
//                        {
//                            $balancetemp->id_carrier_supplier=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
//                        }
//                        break;
//                    case 4:
//                        //minutos
//                        $balancetemp->minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 5:
//                        //ACD
//                        $balancetemp->acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 6:
//                        //ASR
//                        $balancetemp->asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 7:
//                        //Margin %
//                        $balancetemp->margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 8:
//                        //Margin per Min
//                        $balancetemp->margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 9:
//                        //Cost per Min
//                        $balancetemp->cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 10:
//                        //Revenue per Min
//                        $balancetemp->revenue_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 11:
//                        //PDD
//                        $balancetemp->pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 12:
//                        //Imcomplete Calls
//                        $balancetemp->incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 13:
//                        //Imcomplete Calls Ner
//                        $balancetemp->incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 14:
//                        //Complete Calls Ner
//                        $balancetemp->complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 15:
//                        //Complete Calls
//                        $balancetemp->complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 16:
//                        //Calls Attempts
//                        $balancetemp->calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 17:
//                        //Duration Real
//                        $balancetemp->duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 18:
//                        //Duration Cost
//                        $balancetemp->duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 19:
//                        //NER02 Efficient
//                        $balancetemp->ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 20:
//                        //NER02 Seizure
//                        $balancetemp->ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 21:
//                        //PDDCalls
//                        $balancetemp->pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 22:
//                        //Revenue
//                        $balancetemp->revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 23:
//                        //Cost
//                        $balancetemp->cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    case 24:
//                        //Margin
//                        $balancetemp->margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
//                        break;
//                    default:
//                        $balancetemp->date_change=date("Y-m-d");
//                        if($balancetemp->save())
//                        {
//                            $this->error=self::ERROR_NONE;
//                        }
//                        else
//                        {
//                            $this->error=self::ERROR_SAVE_DB;
//                        }
//                }
//            }
//        }
//        /*if($this->error>0)
//        {
//            return false;
//        }
//        else
//        {
//            if(Log::registrarLog(LogAction::getId($accionLog),$balancetemp->date_balance))
//            {
//                $this->error=ERROR_NONE;
//                return true;
//            }
//            else
//            {
//                $this->error=ERROR_SAVE_LOG;
//                return false;
//            }
//            
//        }   */                
//    }
    /**
    * Esta funcion se encarga de definir que nombre darle al archivo al momento de guardarlo en el servidor
    */
    public static function nombre($nombre)
    {
        $primero="Ruta ";
        $segundo="External ";
        $tercero="Diario";
        if(stripos($nombre,"internal"))
        {
            $segundo="Internal ";
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
}
?>