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
    public static function validar($path,$diario,$existentes,$yesterday,$archivo) 
    {
    	//Primero: verifico que archivos estan
		if(count($existentes)<=0)
		{
			self::$error=self::ERROR_FILE;
			self::$errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de diario,<br> verifique que el nombre de los archivos sea Ruta Internal y Ruta External.<h5>";
		}
		//Seguno: verifico el log de archivos diarios, si no esta asigno la variable log para su guardado
		self::logDiario($diario);
		
		if(self::validarFecha($yesterday,$path,$diario,$archivo))
		{
			//Tercero: verifico la fecha que sea correcta
   			if(self::validarColumnas(self::lista($diario),$path,$diario,$archivo))
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
			self::$errorComment="<h5 class='nocargados'> El archivo '".$diario."' tiene una fecha incorrecta </h5> <br/> ";
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
    public static function logDiario($key)
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
            	self::$error=self::ERROR_EXISTS;
                self::$errorComment="<h5 class='nocargados'> El archivo '".$key."' ya esta almacenado </h5> <br/> ";
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
    }

    /**
     * 
     * valida la fecha del archivo
     * @param $fecha
     * @param $directorio
     */
    public static function validarFecha($fecha,$path,$diario,$archivo)
    {
      	$date_balance=strtotime(Utility::formatDate($archivo->excel->sheets[0]['cells'][1][4]));
		$fecha=strtotime($fecha);

        if($fecha==$date_balance)
        {
            self::$error=self::ERROR_NONE;
            return true;
        }
        else
        {
            self:: $error=self::ERROR_DATE;
			self::$errorComment="<h5 class='nocargados'> El archivo '".$diario."' tiene una fecha incorrecta </h5> <br/> ";
  		    return false;
        }
    }
 /**
    * Funcion a la que se le pasa una lista donde el orden incluido debe ser cumplido por el archivo que se esta evaluando
    * @param array $lista lista de elementos que debe cumplir las columnas
    */
    public static function validarColumnas($lista,$path,$diario,$archivo)
    {
    	foreach ($lista as $key => $campo)
        {
        	
            $pos=$key+1;
            if($campo!=$archivo->excel->sheets[0]['cells'][2][$pos])
            {
            	self::$error=self::ERROR_ESTRUC;
                self::$errorComment.="<h5 class='nocargados'> El archivo '".$diario."' tiene la columna ".$archivo->excel->sheets[0]['cells'][2][$pos]." en lugar de ".$campo."</h5> <br/>";
                return false;
            }
        }
        self::$error=self::ERROR_NONE;
        return true;
    }
    
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
    /**
	* Retorna un arreglo con los nombres de las columnas que deberian tener los archivos
	* @param $archivo string nombre del archivo que se va a consultar
	* @return $lista[] array lista de nombres de columnas
	*/ 
	 public static function lista($archivo)
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
        return $lista[$nombre];
	}
	
	 /**
	  *    funcion que crea un string con los datos preliminar
	  * @param fecha $fecha
	  * @param array $var
	  * @return string 
	  */
	public static function loadArchTemp($fecha,$var)
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
	}
	
	/**
	 * guarda la data nueva del archivo
	 * Enter description here ...
	 * @param array $var 
	 */
	 public static function saveDataArch($var)
	 {
	 	$values=$var['values'];
	 	$sql="INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_carrier_supplier, id_destination, id_destination_int, status, id_carrier_customer) VALUES ".$values;
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
    public static function deleteArchTemp($stringDataPreliminary)
    {
    	// borro los registros con el string formado anteriormente
		$sql="DELETE FROM balance where id IN (".$stringDataPreliminary.")";
		$command = Yii::app()->db->createCommand($sql);
		if($command->execute()){
		   self::$error=self::ERROR_NONE;
		}
    }
    
}
?>