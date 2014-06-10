<?php
/**
 * Archivo de clase Reader.
 * @version 6.1
 * @author Manuel Zambrano <mmzmm3z@gmail.com>
 * @copyright 2013 Sacet Todos los derechos reservados.
 * @package components
 */
class ValidationsArchCapt
{
    public $model;
    public $vencom;
    public static $error=0;
    public static $errorComment;
    public $horas;
    public $tipo;
    public $log;
    public $fecha;
    public $nombreArchivo;
    private $nuevos=0;
    private $actualizados=0;
    private $fallas=0;
    private $destino;
    public $excel;
    public $valida;
    
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
     * 
     * funcion que realiza las validaciones antes de insertar los nuevos datos del excel
     * @param unknown_type $path
     * @param unknown_type $diario
     * @param unknown_type $existentes
     * @param unknown_type $yesterday
     */
    public static function validar($path,$nombre,$existentes,$yesterday,$archivo,$tipo) 
    {
    	//Primero: verifico que archivos estan
		if(count($existentes)<=0)
		{
			
			self::$error=self::ERROR_FILE;
			if($tipo=='dia'){
				self::$errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de diario,<br> verifique que el nombre de los archivos sea Ruta Internal y Ruta External.<h5>";	
			}elseif($tipo=='hora'){
				self::$errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de horas,<br> verifique que el nombre de los archivos sea Ruta Internal cant_horas y Ruta External cant_horas.<h5>";
			}
			
		}
		
		//Seguno: verifico el log de archivos diarios, si no esta asigno la variable log para su guardado
		self::logDayHours($nombre,$archivo,$tipo);
		
		if(self::validarFecha($yesterday,$path,$nombre,$archivo,$tipo))
		{
			
			//Tercero: verifico la fecha que sea correcta
   			if(self::validarColumnas(self::lista($nombre,$tipo),$path,$nombre,$archivo,$tipo))
   			{
   				if(self::$error==self::ERROR_NONE){
					return true;
				}else{
					return false;
				}
   			}else{
				self::$error=self::ERROR_ESTRUC;
                return false;
				}
		}else{
			self::$error=self::ERROR_DATE;
			self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre."' tiene una fecha incorrecta </h5> <br/> ";
  		    return false;
		}
	 }
 /**
    * Encargado de traer los nombres de los archivos que coinciden con la lista dada
    * @param $directorio string ruta al directorio que se va a revisar
    * @param $listaArchivos array lista de archivos que se van a buscar en el directorio
    * @param $listaExtensiones array lista de extensiones que pueden tener los archivos
    * @return $confirmados array lista de archivos que hay dentro del directorio consultado que coinciden con la lista dada
    */
    public static function getNombreArchivos($directorio,$listaArchivos,$listaExtensiones)
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
    
   public static function setName($nombre)
    {
     return   $nombreArchivo=$nombre;
    }
    /**
    * Encargada de definir atributos para proceder a la lectura del archivo
    */
    public static function define($nombre)
    {
    	
        if(stripos($nombre,"internal"))
        {
            $tipo="internal";
            $destino="id_destination_int";
            return $tipo;
        }
        else
        {
            $tipo="external";
            $destino="id_destination";
            return $tipo;
        }
    }
	/**
     * Valida que el archivo que se esta leyendo no este en log,
     * si existe deveulve verdadero de lo contrario falso y asigna el valor del log
     * @param $key string con el nombre del archivo que se quiere verificar
     * @return boolean
     */
    public static function logDayHours($key,$tipo)
    {
    	if($tipo=='dia')
    	{
    		$nombre=$key;
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
	            	self::$error=self::ERROR_EXISTS;
	                self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre."' ya esta almacenado </h5> <br/> ";
	                return false;
	            }
	            else
	            {
	            	self::$error=self::ERROR_NONE;
	                $log="Carga Ruta ".$key." Definitivo";
	                return $log;
	            }
	        }
	        else
	        {
	        	self::$error=self::ERROR_NONE;
	            $log="Carga Ruta ".$key." Preliminar";
	            return $log;
	        }
    	}elseif($tipo=='hora')
    	{
    		$numero = explode("Hrs", $key);
		    $numero = explode(" ", $numero[0]);
		    //nombre deñ archivo para buscar su id 
		    $nombre="Carga Ruta ".$numero[1]." ".$numero[2]."GMT";
		    //nombre a mostrar en pantalla
		    $nombre2="Ruta ".$numero[1]." ".$numero[2]."Hrs";
		    //nombre archivo faltante anterior
		    $horas=(int)$numero[2]-4;
		    $nombre3="Ruta ".$numero[1]." ".$horas."Hrs";

 			$date=date('Y-m-d');

		    $idActual= LogAction::getId($nombre);
		    if($idActual>9)
		    {
				$valido=0;
								
				if($idActual==13)
				{ // es 8Hrs busco si ya sta cargado el 4Hrs
					$idAnterior=9;
					$exite=Log::model()->findAll('id_log_action=:idAnterior and date=:fecha',array(':idAnterior'=>$idAnterior, ':fecha'=>$date));
					if($exite) //si est cargado el anterior 4Hrs
					{
					  $valido=1;
					}else
					{
					  $valido=0;
					  self::$error=self::ERROR_EXISTS;
             		  self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' no se puede cargar porque <br>";
					  self::$errorComment.="aun no se ha cargado el archivo '".$nombre3."'</h5> <br/> ";
             		  return false;		
					}
				}
		        elseif($idActual==17)
				{ // es 12Hrs busco si ya sta cargado el 8Hrs
					$idAnterior=13;
					$exite=Log::model()->findAll('id_log_action=:idAnterior and date=:fecha',array(':idAnterior'=>$idAnterior, ':fecha'=>$date));
					if($exite) //si est cargado el anterior 8Hrs
					{
					  $valido=1;
					}else
					{
					  $valido=0;
					  self::$error=self::ERROR_EXISTS;
             		  self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' no se puede cargar porque <br>";
					  self::$errorComment.="aun no se ha cargado el archivo '".$nombre3."'</h5> <br/> ";
             		  return false;	
					}
				}
		    	elseif($idActual==21)
				{ // es 16Hrs busco si ya sta cargado el 12Hrs
					$idAnterior=17;
					$exite=Log::model()->findAll('id_log_action=:idAnterior and date=:fecha',array(':idAnterior'=>$idAnterior, ':fecha'=>$date));
					if($exite) //si est cargado el anterior 12Hrs
					{
					  $valido=1;
					}else
					{
					  $valido=0;
					  self::$error=self::ERROR_EXISTS;
             		  self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' no se puede cargar porque <br>";
					  self::$errorComment.="aun no se ha cargado el archivo '".$nombre3."'</h5> <br/> ";
             		  return false;		
					}
				}
		    	elseif($idActual==25)
				{ // es 20Hrs busco si ya sta cargado el 16Hrs
					$idAnterior=21;
					$exite=Log::model()->findAll('id_log_action=:idAnterior and date=:fecha',array(':idAnterior'=>$idAnterior, ':fecha'=>$date));
					if($exite) //si est cargado el anterior 16Hrs
					{
					  $valido=1;
					}else
					{
					  $valido=0;
					  self::$error=self::ERROR_EXISTS;
             		  self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' no se puede cargar porque <br>";
					  self::$errorComment.="aun no se ha cargado el archivo '".$nombre3."'</h5> <br/> ";
             		  return false;		
					}
				}
		    	elseif($idActual==29)
				{ // es 24Hrs busco si ya sta cargado el 20Hrs
					$idAnterior=25;
					$exite=Log::model()->findAll('id_log_action=:idAnterior and date=:fecha',array(':idAnterior'=>$idAnterior, ':fecha'=>$date));
					if($exite) //si est cargado el anterior 20Hrs
					{
					  $valido=1;
					}else
					{
					  $valido=0;
					  self::$error=self::ERROR_EXISTS;
             		  self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' no se puede cargar porque <br>";
					  self::$errorComment.="aun no se ha cargado el archivo '".$nombre3."'</h5> <br/> ";
             		  return false;		
					}
				}
		    
				    
				

	             	
    		}
		    
          	if(Log::existe(LogAction::getId($nombre)))
          	{
             	self::$error=self::ERROR_EXISTS;
             	self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre2."' ya esta almacenado </h5> <br/> ";
             	return false;
          	}
	      	else
	      	{
	         	self::$error=self::ERROR_NONE;
	         	$log="Carga ".$key." Definitivo";
	         	return $log;
	      	}
  		
    	}
    }
    

    /**
     * 
     * valida la fecha del archivo
     * @param $fecha
     * @param $directorio
     */
    public static function validarFecha($fecha,$path,$nombre,$archivo,$tipo)
    {
    	if($tipo=='dia')
    	{
	    	$date_balance=strtotime(Utility::formatDate($archivo->excel->sheets[0]['cells'][1][4]));
			$fecha=strtotime($fecha);
    	}elseif($tipo=='hora'){
    		$date=date('Y-m-d'); //hora es dia actual
    		$date_balance=strtotime(Utility::formatDate($archivo->excel->sheets[0]['cells'][1][5]));
			$fecha=strtotime($date);
    	}
	        if($fecha==$date_balance)
	        {
	            self::$error=self::ERROR_NONE;
	            return true;
	            
	           
	        }
	        else
	        {
	            self:: $error=self::ERROR_DATE;
				self::$errorComment="<h5 class='nocargados'> El archivo '".$nombre."' tiene una fecha incorrecta </h5> <br/> ";
				return false;
	        }
    }
 /**
    * Funcion a la que se le pasa una lista donde el orden incluido debe ser cumplido por el archivo que se esta evaluando
    * @param array $lista lista de elementos que debe cumplir las columnas
    */
    public static function validarColumnas($lista,$path,$nombre,$archivo,$tipo)
    {
    	 foreach ($lista as $key => $campo)
	        {
	        	$pos=$key+1;
	            if($campo!=$archivo->excel->sheets[0]['cells'][2][$pos])
	            {
	            	self::$error=self::ERROR_ESTRUC;
	                self::$errorComment.="<h5 class='nocargados'> El archivo '".$nombre."' tiene la columna ".$archivo->excel->sheets[0]['cells'][2][$pos]." en lugar de ".$campo."</h5> <br/>";
	                return false;
	            }
	        }
	  
        self::$error=self::ERROR_NONE;
        return true;
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
	* Retorna un arreglo con los nombres de las columnas que deberian tener los archivos
	* @param $archivo string nombre del archivo que se va a consultar
	* @return $lista[] array lista de nombres de columnas
	*/ 
	 public static function lista($archivo,$tipo)
	{
		
		if($tipo=='dia')
		{
			$primero="Ruta ";
	        $segundo="External ";
	        $tercero="Diario";
	        if(stripos($archivo,"internal"))
	        {
	            $segundo="Internal ";
	        }
	        if(stripos($archivo,'rerate') || stripos($archivo, "RR"))
	        {
	            $tercero="RR";
	        }
	        if(stripos($archivo,'GMT'))
	        {
	            $tercero="Hora";
	        }
	        $nombre=$primero.$segundo.$tercero;
	        $lista=array(
	        	'Ruta Internal Diario'=>array('Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
	        	'Ruta External Diario'=>array('Ext. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
	        	);
		}elseif($tipo=='hora')
		{	
		
        	$primero="Ruta ";
            if(stripos($archivo,"internal"))
	        {
	            $segundo="Internal ";
	        }
            if(stripos($archivo,"external"))
	        {
	            $segundo="External ";
	        }
	        $numero = explode("Hrs", $archivo);
	        $numero = explode(" ", $numero[0]);
			$nombre=$primero.$segundo.$numero[2]."Hrs";
     
        $lista=array(
        	'Ruta Internal 4Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta Internal 8Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta Internal 12Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta Internal 16Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta Internal 20Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta Internal 24Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
       		'Ruta External 4Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta External 8Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta External 12Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta External 16Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta External 20Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        	'Ruta External 24Hrs'=>array('Hour','Int. Dest','Customer','Supplier','Minutes','ACD','ASR','Margin %','Margin per Min','Cost per Min','Revenue per Min','PDD','Incomplete Calls','Incomplete Calls NER','Complete Calls NER','Complete Calls','Call Attempts','Duration Real','Duration Cost','NER02 Efficient','NER02 Seizure','PDDCalls','Revenue','Cost','Margin'),
        
        	
        	);
		}
        return $lista[$nombre];
	}
	
	 /**
	  *    funcion que crea un string con los datos preliminar
	  * @param fecha $fecha
	  * @param array $var
	  * @return string 
	  */
	public static function loadArchTemp($fecha,$var,$tipo,$archivo)
    {
    	if($tipo=='dia')
    	{
	    	// busco y lleno un array con los datos que estan en bd antes de eliminrlos
	    	//le mando $id_destination,$id_destination_int para saber cual se esta guardando si internal o external
	       $id_destination=$var['id_destination'];
	       $id_destination_int=$var['id_destination_int'];
		   if($id_destination=='NULL'){ // ES interno 
		     $name_destination='id_destination';
		    }elseif($id_destination_int=='NULL'){ //es externo 
	    	 $name_destination='id_destination_int';
		   }
		   $total=0;
		   $total= Balance::model()->count('date_balance=:fecha',array(':fecha'=>$fecha));
		   if($total>0)//si ya hay registros del dia, guardo sus id en un string para borrarlos luego de insertar los nuevos
		   {
		     $results=Balance::model()->findAll('date_balance=:fecha and '.$name_destination.' is NULL',array(':fecha'=>$fecha));
	         $v=array();
			 $values="";
		  	 foreach($results as $x=>$row) {
			 $v[]=$row->id;
				}
			 $values=implode(",", $v);  //convierto el array en un string con los id separados por (,)
			
	         if($values==""){
	     	  $values="";
	         }
			}else{
				$values="";
			}
			return  $values;
			
      	}elseif($tipo=='hora')
    	{
		     	
		    //hora inicial para buscar y borrar para luego agregar las actualizadas
	        $horas=$archivo->excel->sheets[0]['cells'][5][1];

	        $date=date('Y-m-d');
	        $total=0;
		    $total= BalanceTime::model()->count('date_balance_time=:fecha',array(':fecha'=>$date));
		   
		    if($total>0)//si ya hay registros del dia, guardo sus id en un string para borrarlos luego de insertar los nuevos
		    {
		    	$results=BalanceTime::model()->findAll('date_balance_time=:fecha and time>=:hora ',array(':fecha'=>$date, ':hora'=>$horas));
		        $v=array();
				$values="";
			  	foreach($results as $x=>$row) {
				$v[]=$row->id;
				}

				$values=implode(",", $v);  //convierto el array en un string con los id separados por (,)
				if($values==""){
		     	  $values="";
		         }
				}else{
					$values="";
				}
				return  $values;
		    }
 	}
	
	/**
	 * guarda la data nueva del archivo
	 * Enter description here ...
	 * @param array $var 
	 */
	 public static function saveDataArchDayHours($var,$tipo)
	 {
	 	
	 	if($tipo=='dia')
		{
		  $values=$var['values'];
		  $sql="INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_carrier_supplier, id_destination, id_destination_int, status, id_carrier_customer) VALUES ".$values;

		}elseif($tipo=='hora')
		{ 
		  $values=$var['regHora'];
		  $sql="INSERT INTO balance_time(date_balance_time,time, minutes,acd,asr,margin_percentage,margin_per_minute,cost_per_minute,revenue_per_minute,pdd,incomplete_calls,incomplete_calls_ner,complete_calls,complete_calls_ner,calls_attempts,duration_real,duration_cost,ner02_efficient,ner02_seizure,pdd_calls,revenue,cost,margin,date_change,time_change,name_supplier,name_customer,name_destination) VALUES ".$values;

		}
	 	$command = Yii::app()->db->createCommand($sql);
			    
		if($command->execute())
        {
            self::$error=self::ERROR_NONE;
            return true;
        }
        else
        {
            self::$error=self::ERROR_SAVE_DB;
            return false;
        }
	  }
	
	/**
	 * funcion que borra el string generado con los datos preliminar
	 * @param string $stringDataPreliminary
	 */
    public static function deleteArchTempDayHours($stringDataPreliminary,$tipo)
    {
    	if($tipo=='dia')
		{
	    	// borro los registros con el string formado anteriormente
			$sql="DELETE FROM balance where id IN (".$stringDataPreliminary.")";
		}elseif($tipo=='hora')
		{
			// borro los registros con el string formado anteriormente
			$sql="DELETE FROM balance_time where id IN (".$stringDataPreliminary.")";
		}	
		$command = Yii::app()->db->createCommand($sql);
		if($command->execute())
		{
		 self::$error=self::ERROR_NONE;
		}
	}
    
   
    
}
?>