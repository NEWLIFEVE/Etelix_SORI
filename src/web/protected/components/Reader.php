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
    public static $error=0; 
    public static $fallas=0; 
    public static $actualizados=0; 
    public static $nuevos=0; 
    public static $errorComment;
    
    /**
     * Agrega al objeto del reader el archivo excel que se va a grabar
     * @param $ruta string ubicacion del archivo
     */
    function __construct($ruta)
    {
       //importo la extension
        Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
        //oculto errores
        error_reporting(E_ALL ^ E_NOTICE);
        $this->excel = new Spreadsheet_Excel_Reader();
        //uso esta codificacion ya que dio problemas usando utf-8 directamente
        $this->excel->setOutputEncoding('ISO-8859-1');
        $this->excel->read($ruta);
        
    }

    /**
     *
     */
    public function setName($nombre)
    {
        $this->nombreArchivo=$nombre;
    }
    
    /**
     * Agrega al objeto del reader el archivo excel que se va a grabar
     * @param $ruta string ubicacion del archivo
     */
    public function carga($ruta)
    {
        //importo la extension
        Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
        //oculto errores
        error_reporting(E_ALL ^ E_NOTICE);

        $this->excel = new Spreadsheet_Excel_Reader();
        //uso esta codificacion ya que dio problemas usando utf-8 directamente
        $this->excel->setOutputEncoding('ISO-8859-1');
        $this->excel->read($ruta);
    }

    /**
    * Funcion de carga de archivos diarios
    * @param string $ruta: ruta absoluta de archivo que va a ser leido
    * @return boolean
    */
    public static function diario($fecha_diario,$nombre,$archivo)
    {
        $values='';
        $var=null;
        ini_set('max_execution_time', 1200);
        for($i=5;$i<=$archivo->excel->sheets[0]['numRows'];$i++)
        {
            $valores=array();
            for($j=1;$j<=$archivo->excel->sheets[0]['numCols'];$j++)
            {
                switch($j)
                {
                    case 1:
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            if(ValidationsArchCapt::define($nombre)=="external")
                            {
                                //Obtengo el id de destino externo
                                $valores['id_destination']=Destination::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                                $valores['id_destination_int']='NULL';                              
                            }
                            else
                            {
                                //obtengo el id del destino interno
                                $valores['id_destination_int']=DestinationInt::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                                $valores['id_destination']='NULL';
                            }
                            $id_destination_int=$valores['id_destination_int'];
                            $id_destination=$valores['id_destination'];  
                        }
                        break;
                    case 2:
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $valores['id_carrier_customer']=Carrier::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 3:
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //Si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $valores['id_carrier_supplier']=Carrier::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 4:
                        //minutos
                        $valores['minutes']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 5:
                        //ACD
                        $valores['acd']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 6:
                        //ASR
                        $valores['asr']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7:
                        //Margin %
                        $valores['margin_percentage']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8:
                        //Margin per Min
                        $valores['margin_per_minute']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9:
                        //Cost per Min
                        $valores['cost_per_minute']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10:
                        //Revenue per Min
                        $valores['revenue_per_minute']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11:
                        //PDD
                        $valores['pdd']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12:
                        //Imcomplete Calls
                        $valores['incomplete_calls']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13:
                        //Imcomplete Calls Ner
                        $valores['incomplete_calls_ner']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14:
                        //Complete Calls Ner
                        $valores['complete_calls_ner']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15:
                        //Complete Calls
                        $valores['complete_calls']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16:
                        //Calls Attempts
                        $valores['calls_attempts']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17:
                        //Duration Real
                        $valores['duration_real']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18:
                        //Duration Cost
                        $valores['duration_cost']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19:
                        //NER02 Efficient
                        $valores['ner02_efficient']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20:
                        //NER02 Seizure
                        $valores['ner02_seizure']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21:
                        //PDDCalls
                        $valores['pdd_calls']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22:
                        //Revenue
                        $valores['revenue']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23:
                        //Cost
                        $valores['cost']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 24:
                        //Margin
                        $valores['margin']=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
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
        
            /* verifico que la linea leida no sea total, para colocar la coma despues de armar la cadena  */   
            if($i<$archivo->excel->sheets[0]['numRows'] && $archivo->excel->sheets[0]['cells'][$i][$j]!='Total')
            {
                $values.=",";
            }

        }//fin de for de $i

        // obtengo el ultimo caracter de la cadena
        $barra = substr($values, -1, 1);
        /*Verifico el ultimo caracter de la cadena es (,) */
        if($barra==",") 
        {
            // se elimina la coma.
            $values=substr($values, 0, - 1);     
        }

        if($values!="")
        {
            $var['values']=$values;
            $var['id_destination']=$id_destination;
            $var['id_destination_int']=$id_destination_int;
        }
        return $var;
    }

    /**
     * Funcion de carga de archivos hora
     * @return boolean
     */
    public static function hora ($archivo)
    {
         /**
        * Valido la estructura de horas
        */
        //hora por la cual inicia el archivo
        $actual=$archivo->excel->sheets[0]['cells'][5][1];
//      $actual=0;
        $contador=0;
         //Cuantos segundos
        $regAprox=1500*$archivo->excel->sheets[0]['cells']['numRows'][1];

        $segundos=$regAprox/2.8;
        $segundos=substr($segundos,0,4);
        //Aumento el tiempo de ejecucion
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', $segundos);
        for ($i=5; $i<$archivo->excel->sheets[0]['numRows']; $i++)
        { 
            if($archivo->excel->sheets[0]['cells'][$i][1]!="Total" && $archivo->excel->sheets[0]['cells'][$i][1]!="Date" && $archivo->excel->sheets[0]['cells'][$i][1]!="Hour")
            {
                //Verifico que sean secuenciales las horas
                if($actual <= $archivo->excel->sheets[0]['cells'][$i][1])
                {
                    if($actual==$archivo->excel->sheets[0]['cells'][$i][1])
                    {
                        $contador=$contador+1;
                    }
                    elseif($actual==$archivo->excel->sheets[0]['cells'][$i][1]-1)
                    {
                        if($contador<=1)
                        {
                            self::$error=ValidationsArchCapt::ERROR_ESTRUC;
                            return false;
                        }
                        else
                        {
                            $contador=0;
                            $actual=$archivo->excel->sheets[0]['cells'][$i][1];
                        }
                    }
                    else
                    {
                        self::$error=ValidationsArchCapt::ERROR_ESTRUC;
                        return false; 
                    }
                }
            }
        }
         
        //Cuantos segundos
        $regAprox=1500*$archivo->excel->sheets[0]['cells']['numRows'][1];
        $segundos=$regAprox/2.8;
        $segundos=substr($segundos,0,4);
        //Aumento el tiempo de ejecucion
        ini_set('max_execution_time', $segundos);
//        ini_set("memory_limit","128M");

        
        /**
         * Verifico que la fecha del archivo sea correcta
         */
        $date_balance_time=Utility::formatDate($data->sheets[0]['cells'][1][5]);
        
        /**
         * Comienzo a leer el archivo
         */
        $valuesNew='';
        $var=array();
        for($i=5;$i<=$archivo->excel->sheets[0]['numRows'];$i++)
        {
            for($j=1;$j<=$archivo->excel->sheets[0]['numCols'];$j++)
            {
                switch($j)
                {
                    case 1:
                        //Obtengo la hora del registro
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total es que se termino el archivo
                            break 3;
                        }
                        else
                        {
                            $time=$archivo->excel->sheets[0]['cells'][$i][$j];
                        }
                        break;
                    case 2:
                        //Obtengo el nombre del destino
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo voy a guardar en base de datos
                            break 2;
                        }
                        else
                        {
                            // $name_destination=utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]);
                            $name_destination=Destination::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 3:
                        //Obtengo el nombre del customer
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo voy a guardar en base de datos
                            break 2;
                        }
                        else
                        {
                            //Aqui encodeo el nombre del carrier a utf-8
                            // $name_customer=utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]);
                            $name_customer=Carrier::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));

                        }
                        break;
                    case 4:
                        if($archivo->excel->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //si es total no lo voy guardar en base de datos
                            break 2;
                        }
                        else
                        {
                            // $name_supplier=utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]);
                            $name_supplier=Carrier::getId(utf8_encode($archivo->excel->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 5;
                        //minutos
                        $minutes=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                    case 6;
                        //ACD 
                        $acd=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7;
                        //ASR
                        $asr=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8;
                        //Margin %
                        $margin_percentage=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9;
                        //Margin per Min
                        $margin_per_minute=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10;
                        //Cost per Min
                        $cost_per_minute=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11;
                        //Revenue per Min
                        $revenue_per_minute=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12;
                        //PDD
                        $pdd=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13;
                        //Imcomplete Calls
                        $incomplete_calls=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14;
                        //Imcomplete Calls Ner
                        $incomplete_calls_ner=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15;
                        //Complete Calls Ner
                        $complete_calls_ner=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16;
                        //Complete Calls
                        $complete_calls=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17;
                        //Calls Attempts
                        $calls_attempts=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18;
                        //Duration Real
                        $duration_real=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19;
                        //Duration Cost
                        $duration_cost=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20;
                        //NER02 Efficient
                        $ner02_efficient=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21;
                        //NER02 Seizure
                        $ner02_seizure=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22;
                        //PDDCalls
                        $pdd_calls=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23;
                        //Revenue
                        $revenue=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 24;
                        //Cost
                        $cost=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 25;
                        //Margin
                        $margin=Utility::notNull($archivo->excel->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                     case 26;
                            $valuesNew.="(";
                            $valuesNew.="'".$date_balance_time."',";
                            $valuesNew.=$time.",";
                            $valuesNew.=$minutes.",";
                            $valuesNew.=$acd.",";
                            $valuesNew.=$asr.",";
                            $valuesNew.=$margin_percentage.",";
                            $valuesNew.=$margin_per_minute.",";
                            $valuesNew.=$cost_per_minute.",";
                            $valuesNew.=$revenue_per_minute.",";
                            $valuesNew.=$pdd.",";
                            $valuesNew.=$incomplete_calls.",";
                            $valuesNew.=$incomplete_calls_ner.",";
                            $valuesNew.=$complete_calls_ner.",";
                            $valuesNew.=$complete_calls.",";
                            $valuesNew.=$calls_attempts.",";
                            $valuesNew.=$duration_real.",";
                            $valuesNew.=$duration_cost.",";
                            $valuesNew.=$ner02_efficient.",";
                            $valuesNew.=$ner02_seizure.",";
                            $valuesNew.=$pdd_calls.",";
                            $valuesNew.=$revenue.",";
                            $valuesNew.=$cost.",";
                            $valuesNew.=$margin.",";
                            $valuesNew.="'".date("Y-m-d")."',";
                            $valuesNew.="'".date("H:i:s")."',";
                            $valuesNew.="'".$name_supplier."',";
                            $valuesNew.="'".$name_customer."',";
                            $valuesNew.="'".$name_destination."')";
                            break;  
                }
            }
            if($i<=$archivo->excel->sheets[0]['numRows']-1)
            {
                $valuesNew.=",";
            }
        }
        $barra = substr($valuesNew, -1, 1);
        if($barra==",") 
        {
            $valuesNew=substr($valuesNew, 0, - 1);     
        }
        if($valuesNew!="")
        {
            $var['regHora']=$valuesNew;
            $var['hora']=$time;
        }
        else
        {
            $var="";
        }
        return $var;
    }

    /*
    * Funcion de carga de archivos de rerate
    */
    public function rerate($ruta,$accionLog)
    {
        //Aumento el tiempo de ejecucion
        //ini_set('max_execution_time', 1200);
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
            $balancetemp->date_balance=Utility::formatDate($data->sheets[0]['cells'][1][4]);
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
                        else
                        {
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
                            $balancetemp->id_carrier_customer=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 3:
                        //obtengo el id del carrier y antes lo codifico a utf-8 para no dar problemas con la funcion
                        if($data->sheets[0]['cells'][$i][$j]=='Total')
                        {
                            //Si es total no lo guardo en base de datos
                            break 2;
                        }
                        else
                        {
                            $balancetemp->id_carrier_supplier=Carrier::getId(utf8_encode($data->sheets[0]['cells'][$i][$j]));
                        }
                        break;
                    case 4:
                        //minutos
                        $balancetemp->minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 5:
                        //ACD
                        $balancetemp->acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 6:
                        //ASR
                        $balancetemp->asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 7:
                        //Margin %
                        $balancetemp->margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 8:
                        //Margin per Min
                        $balancetemp->margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 9:
                        //Cost per Min
                        $balancetemp->cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 10:
                        //Revenue per Min
                        $balancetemp->revenue_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 11:
                        //PDD
                        $balancetemp->pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 12:
                        //Imcomplete Calls
                        $balancetemp->incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 13:
                        //Imcomplete Calls Ner
                        $balancetemp->incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 14:
                        //Complete Calls Ner
                        $balancetemp->complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 15:
                        //Complete Calls
                        $balancetemp->complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 16:
                        //Calls Attempts
                        $balancetemp->calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 17:
                        //Duration Real
                        $balancetemp->duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 18:
                        //Duration Cost
                        $balancetemp->duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 19:
                        //NER02 Efficient
                        $balancetemp->ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 20:
                        //NER02 Seizure
                        $balancetemp->ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 21:
                        //PDDCalls
                        $balancetemp->pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 22:
                        //Revenue
                        $balancetemp->revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 23:
                        //Cost
                        $balancetemp->cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    case 24:
                        //Margin
                        $balancetemp->margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
                        break;
                    default:
                        $balancetemp->date_change=date("Y-m-d");
                        if($balancetemp->save())
                        {
                            $this->error=self::ERROR_NONE;
                        }
                        else
                        {
                            $this->error=self::ERROR_SAVE_DB;
                        }
                }
            }
        }
        /*if($this->error>0)
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
            
        }   */                
    }

    /**
    * Esta funcion se encarga de definir que nombre darle al archivo al momento de guardarlo en el servidor
    */
    public function nombre($nombre)
    {
        $primero="Ruta ";
        $segundo="External ";
        $tercero="Diario";
        if(stripos($nombre,"internal"))
        {
            $segundo="Internal ";
            $nuevoNombre=$primero.$segundo.$tercero;
        }
        if(stripos($nombre,"external"))
        {
            $segundo="External ";
            $nuevoNombre=$primero.$segundo.$tercero;
        }
        if(stripos($nombre,'rerate') || stripos($nombre, "RR"))
        {
            $tercero="RR";
            $nuevoNombre=$primero.$segundo.$tercero;
        }
        if(stripos($nombre,'GMT'))
        {
            $tercero="Hora";
            $nuevoNombre=$primero.$segundo.$tercero;
        }
        if(stripos($nombre,"Hrs"))
        {
            $nuevoNombre=$nombre;
        }
//        $nuevoNombre=$primero.$segundo.$tercero;
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
            $this->destino="id_destination_int";
        }
        else
        {
            $this->tipo="external";
            $this->destino="id_destination";
        }
    }

    /**
    * Funcion a la que se le pasa una lista donde el orden incluido debe ser cumplido por el archivo que se esta evaluando
    * @param array $lista lista de elementos que debe cumplir las columnas
    */
    public function validarColumnas($lista)
    {
        foreach ($lista as $key => $campo)
        {
            $pos=$key+1;
            if($campo!=$this->excel->sheets[0]['cells'][2][$pos])
            {
                $this->error=self::ERROR_ESTRUC;
                $this->errorComment.="<h5 class='nocargados'> El archivo '".$this->nombreArchivo."' tiene la columna ".$this->excel->sheets[0]['cells'][2][$pos]." en lugar de ".$campo."</h5> <br/>";
                return false;
            }
        }
        $this->error=self::ERROR_NONE;
        return true;
    }

    /**
    * Encargado de traer los nombres de los archivos que coinciden con la lista dada
    * @param $directorio string ruta al directorio que se va a revisar
    * @param $listaArchivos array lista de archivos que se van a buscar en el directorio
    * @param $listaExtensiones array lista de extensiones que pueden tener los archivos
    * @return $confirmados array lista de archivos que hay dentro del directorio consultado que coinciden con la lista dada
    */
    public function getNombreArchivos($directorio,$listaArchivos,$listaExtensiones)
    {
        $confirmados=array();
        if($directorio==null)
        {
            return false;
        }
        else
        {
            $archivos=@scandir($directorio);
            foreach($listaArchivos as $keyAr => $nombreLista)
            {
                foreach($archivos as $keyDir => $archivo)
                {
                    foreach($listaExtensiones as $keyEx => $extension)
                    {
                        $temp=$nombreLista.".".$extension;
                        if($temp == $archivo)
                        {
                            $confirmados[$keyAr]=$temp;
                        }
                    }
                }
            }
            return $confirmados;
        }
    }

    /**
     * Valida que el archivo que se esta leyendo no este en log,
     * si existe deveulve verdadero de lo contrario falso y asigna el valor del log
     * @param $key string con el nombre del archivo que se quiere verificar
     * @return boolean
     */
    public function logDiario($key)
    {
        if(stripos($key,"internal"))
        {
            $key='Internal';
        }
        else
        {
            $key='External';
        }
        if(Log::existe(LogAction::getLikeId('%'.$key.'%Preliminar%')))
        {
            if(Log::existe(LogAction::getLikeId('%'.$key.'%Definitivo%')))
            {
                $this->error=self::ERROR_EXISTS;
                $this->errorComment="<h5 class='nocargados'> El archivo '".$key."' ya esta almacenado </h5> <br/> ";
                return true;
            }
            else
            {
                $this->error=self::ERROR_NONE;
                $this->log="Carga Ruta ".$key." Definitivo";
                return false;
            }
        }
        else
        {
            $this->error=self::ERROR_NONE;
            $this->log="Carga Ruta ".$key." Preliminar";
            return false;
        }
    }

    /**
    *
    */
/*    public function validarFecha($fecha)
    {
        $date_balance=strtotime(Utility::formatDate($this->excel->sheets[0]['cells'][1][4]));
        $this->fecha=$fecha;
        $fecha=strtotime($fecha);
        if($fecha==$date_balance)
        {
            $this->error=self::ERROR_NONE;
            return true;
        }
        else
        {
            $this->error=self::ERROR_DATE;
            $this->errorComment="<h5 class='nocargados'> El archivo '".$this->nombreArchivo."' tiene una fecha incorrecta </h5> <br/> ";
            return false;
        }
    }*/
}
?>
