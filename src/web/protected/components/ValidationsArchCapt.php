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
    public $error=0;
    public $errorComment;
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
     */
    
    public function validar($path,$diario,$existentes,$yesterday) 
    {
    	//Primero: verifico que archivos están
        //$existentes=$this->getNombreArchivos($path,$diarios,array('xls','XLS'));
		
		if(count($existentes)<=0)
		{
			$this->error=4;
			$this->errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de diario,<br> verifique que el nombre de los archivos sea Ruta Internal y Ruta External.<h5>";
		}
		if(Log::existe(LogAction::getLikeId('Carga Ruta External Preliminar')))
		{
			Balance::model()->deleteAll('date_balance=:date AND id_destination_int IS NULL', array(':date'=>$yesterday));
		}
		if(Log::existe(LogAction::getLikeId('Carga Ruta Internal Preliminar')))
		{
			Balance::model()->deleteAll('date_balance=:date AND id_destination IS NULL', array(':date'=>$yesterday));
		}
		
        $this->setName($diario);
		//Defino variables internas
		$this->define($diario);
		//Seguno: verifico el log de archivos diarios, si no esta asigno la variable log para su guardado
		$this->logDiario($diario);
		if($this->error==0)
		{
			//cargo el archivo en memoria
			$this->carga($path.$diario);
			//Tercero: verifico la fecha que sea correcta
			$this->validarFecha($yesterday);
		}
		if($this->error==0)
		{
			//Cuarto: valido el orden de las columnas
			$this->validarColumnas($this->lista($diario));
		}
		
		if($this->error==0){
			return true;
		}else{
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
    
    protected  function setName($nombre)
    {
        $this->nombreArchivo=$nombre;
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
    *
    */
    public function validarFecha($fecha)
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
	protected function lista($archivo)
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
	
	//   funcion que crea un string con los datos preliminar
	public static function load_arch_temp($fecha,$id_destination,$id_destination_int)
    {
       $connection=Yii::app()->db;
       //verifico si hay algun registro prelminar del dia guardado 
       //falta identificar si es inter o exter para no borrar esos registros
       $sql1="select count(id) as count_records from balance where date_balance='".$fecha."'";
       $command=$connection->createCommand($sql1);
	   $res=$command->queryRow();					
	   $count_records=0;
	   if($res){
		   $count_records=$res['count_records'];
	   }		
		
	   if($count_records>0)
	   {
	   	   if($id_destination==NULL){ // ES interno de acuerdo a lo que voy a guardar
	    	  $sql="select id,id_destination,id_destination_int from balance where id_destination is NULL and date_balance='".$fecha."'";
		   }elseif($id_destination_int==NULL){ //es externo de acuerdo a lo que voy a guardar
    	     $sql="select id,id_destination,id_destination_int from balance where id_destination_int is NULL anddate_balance='".$fecha."'";
		   }
	   	   //string para guardar los id (datos preliminar) antes de borrarlos
	     
	       $command=$connection->createCommand($sql);
	       $results=$command->queryAll();
	       $i=0; 
	       foreach($results as $x=>$row) 
			{
			  $values.=" id=".$row['id']." ";
			  if($i<$count_records-1)
			  {
			   $values.=" or";
			  }
			  $i++;
			 }
		 
		}
        return  $values;
	}
	
	//funcion que borra el string generado con los datos preliminar
    public static function delete_arch_temp($string_data_preliminar)
    {
    	// busco si estan cargados los internos o externos  para borrarlo
        $sql="DELETE FROM balance where ".$cadena." ";
		$command = Yii::app()->db->createCommand($sql);
		if($command->execute()){
		   $this->error=self::ERROR_NONE;
		}
    }
    
}
?>