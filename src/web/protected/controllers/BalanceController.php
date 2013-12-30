<?php

class BalanceController extends Controller
{
	/**
	 * Atributo para instanciar el componente reader
	 */
	public $lector;
	private $nombre;

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // Vistas para Administrador
				'actions'=>array('index','view','admin','delete','create','update','ventas','compras','carga', 'guardar', 'ver', 'memoria','upload','delete'),
				'users'=>array_merge(Users::usersByType(1)),
				),
			array('allow', // Vistas para NOC
				'actions'=>array('index','guardar','upload','carga'),
				'users'=>array_merge(Users::usersByType(2)),
				),
			array('allow', // Vistas para Operaciones
				'actions'=>array('index','view','admin','delete','create','update','ventas','compras', 'guardar', 'ver', 'memoria','upload','delete'),
				'users'=>array_merge(Users::usersByType(3)),
				),
			array('allow', // Vistas para Finanzas
				'actions'=>array(''),
				'users'=>array_merge(Users::usersByType(4)),
				),
			array('allow', // Vistas para Retail
				'actions'=>array(''),
				'users'=>array_merge(Users::usersByType(5)),
				),
			array('allow',
				'actions'=>array(
					'uploadtemp',
					'cargatemp',
					'guardartemp'
					),
				'users'=>array(
					'fabianar'
					)
				),
			array('deny',  // deny all users
				'users'=>array('*'),
				),

			);
	}

	/**
	 * Muestra una vista con los balances especificados por compras
	 */
    public function actionCompras()
	{
		$model=new Balance;
		$this->render('compras',array('model'=>$model));
	}

	/**
	 * Muestra una vista con los balances especificados por ventas
	 */
	public function actionVentas()
	{
		$model=new Balance;
		$this->render('ventas',array('model'=>$model));
	}
	/**
	 *
	 */
	public function actionUpload()
	{
		//Cada vez que el usuario llegue al upload se verificaran si hay archivos en la carpeta uploads y se eliminaran
		$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR;
		if(is_dir($ruta))
		{
			$archivos=@scandir($ruta);
		}
		if(count($archivos)>1)
		{
			foreach($archivos as $key => $value)
			{
				if($key>1)
				{ 
					if($value!='index.html' && $value!='temp')
					{
						unlink($ruta.$value);
					}
				}
			}
		}
		$this->render('upload');               
	}

	public function actionUploadtemp()
	{
		//Cada vez que el usuario llegue al upload se verificaran si hay archivos en la carpeta uploads y se eliminaran
		$ruta=Yii::getPathOfAlias('webroot.uploads.temp').DIRECTORY_SEPARATOR;
		if(is_dir($ruta))
		{
			$archivos=@scandir($ruta);
		}
		if(count($archivos)>1)
		{
			foreach($archivos as $key => $value)
			{
				if($key>1)
				{ 
					if($value!='index.html')
					{
						unlink($ruta.$value);
					}
				}
			}
		}
		$this->render('uploadtemp');               
	}

	public function actionCargatemp()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");

		$folder='uploads/temp/';// folder for uploaded files
        $allowedExtensions = array("xls", "xlsx");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 20 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
 
        $fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
        $fileName=$result['filename'];//GETTING FILE NAME
 
        echo $return;// it's array
	}

	public function actionGuardartemp()
	{

		//Delclarando variables utiles para el codigo
		$ruta=Yii::getPathOfAlias('webroot.uploads.temp').DIRECTORY_SEPARATOR;
		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";
		//instancio el componente
		$this->lector=new Reader;
		//Nombres opcionales para los archivos diarios
		$diarios=array(
			'Carga Ruta Internal'=>'Ruta Internal Diario',
			'Carga Ruta External'=>'Ruta External Diario'
			);

		//Primero: verifico que archivos están
		$existentes=$this->lector->getNombreArchivos($ruta,$diarios,array('xls','XLS'));
		if(count($existentes)<=0)
		{
			$this->lector->error=4;
			$this->lector->errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de diario,<br> verifique que el nombre de los archivos sea Ruta Internal y Ruta External.<h5>";
		}
		//Si la primera condicion se cumple, no deberian haber errores
		if($this->lector->error==0)
		{
			foreach($existentes as $key => $diario)
			{
				$this->lector->setName($diario);
				//Defino variables internas
				$this->lector->define($diario);
				//cargo el archivo en memoria
				$this->lector->carga($ruta.$diario);
				//Tercero: verifico la fecha que sea correcta
				$this->lector->fecha=Utility::formatDate($this->lector->excel->sheets[0]['cells'][1][4]);
				//Cuarto: valido el orden de las columnas
				$this->lector->validarColumnas($this->lista($diario));
				if($this->lector->error==0)
				{
					$this->lector->diario();
				}
				if($this->lector->error>0)
				{
					$fallas.=$this->lector->errorComment;
				}
				if($this->lector->error==0)
				{
					$exitos.="<h5 class='cargados'> El arhivo '".$diario."' se guardo con exito </h5> <br/>";
				}
				$this->lector->error=0;
				$this->lector->errorComment=NULL;
			}
		}
		if($this->lector->error>0)
		{
			$fallas.=$this->lector->errorComment;
		}
		$resultado.=$exitos."</br>".$fallas."</div>";
       	$this->render('guardar',array('data'=>$resultado));
	}

	/**
	 * Muestra el detalle de un balance
	 * @param $id el id del balance que va a mostrar
	 */
	public function actionView($id)
	{
		$tipo=Balance::model()->findByPk($id)->type;
		if($tipo==TRUE)
			$nombre="Compra";
		else
			$nombre="Venta";
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'nombre'=>$nombre,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Balance;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Balance']))
		{
			$model->attributes=$_POST['Balance'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Balance']))
		{
			$model->attributes=$_POST['Balance'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
        
    /**
     *
     */
    public function actionCarga()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");

		$folder='uploads/';// folder for uploaded files
        $allowedExtensions = array("xls", "xlsx");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 20 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
 
        $fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
        $fileName=$result['filename'];//GETTING FILE NAME
 
        echo $return;// it's array
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->nombre="Hola";
		$dataProvider=new CActiveDataProvider('Balance');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Balance('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Balance']))
			$model->attributes=$_GET['Balance'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Balance the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Balance::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Balance $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='balance-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Action encargada de guardar en base de datos los archivos cargados
	 */
	public function actionGuardar()
	{
		$path=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR;
		$date=date('Y-m-d');
		$yesterday=strtotime('-1 day',strtotime($date));
		$yesterday=date('Y-m-d',$yesterday);

		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";

        //Verfico si el arreglo post esta seteado
		if(isset($_POST['tipo']))
		{
			//Verifico la opcion del usuario a través del post
			//si la opcion es día
			if($_POST['tipo']=="dia")
			{
				//instancio el componente
				$this->lector=new Reader;
				//Nombres opcionales para los archivos diarios
				$diarios=array(
					'Carga Ruta Internal'=>'Ruta Internal Diario',
					'Carga Ruta External'=>'Ruta External Diario'
					);

				//Primero: verifico que archivos están
				$existentes=$this->lector->getNombreArchivos($path,$diarios,array('xls','XLS'));
				if(count($existentes)<=0)
				{
					$this->lector->error=4;
					$this->lector->errorComment="<h5 class='nocargados'>No se encontraron archivos para la carga de diario,<br> verifique que el nombre de los archivos sea Ruta Internal y Ruta External.<h5>";
				}
				if(Log::existe(LogAction::getLikeId('Carga Ruta External Preliminar')))
				{
					Balance::model()->deleteAll('date_balance=:date AND id_destination_int IS NULL', array(':date'=>$yesterday));
				}
				if(Log::existe(LogAction::getLikeId('Carga Ruta Internal Preliminar')))
				{
					Balance::model()->deleteAll('date_balance=:date AND id_destination IS NULL', array(':date'=>$yesterday));
				}
				//Si la primera condicion se cumple, no deberian haber errores
				if($this->lector->error==0)
				{
					foreach($existentes as $key => $diario)
					{
						$this->lector->setName($diario);
						//Defino variables internas
						$this->lector->define($diario);
						//Seguno: verifico el log de archivos diarios, si no esta asigno la variable log para su guardado
						$this->lector->logDiario($diario);
						if($this->lector->error==0)
						{
							//cargo el archivo en memoria
							$this->lector->carga($path.$diario);
							//Tercero: verifico la fecha que sea correcta
							$this->lector->validarFecha($yesterday);
						}
						if($this->lector->error==0)
						{
							//Cuarto: valido el orden de las columnas
							$this->lector->validarColumnas($this->lista($diario));
						}
						if($this->lector->error==0)
						{
							//Guardo en base de datos
							if($this->lector->diario())
							{
								//Si lo guarda grabo en log
								Log::registrarLog(LogAction::getId($this->lector->log));
							}
						}
						if($this->lector->error>0)
						{
							$fallas.=$this->lector->errorComment;
						}
						if($this->lector->error==0)
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$diario."' se guardo con exito </h5> <br/>";
						}
						$this->lector->error=0;
						$this->lector->errorComment=NULL;
					}
				}
				if($this->lector->error>0)
				{
					$fallas.=$this->lector->errorComment;
				}
			}
			//Si la opcion es hora
			elseif($_POST['tipo']=="hora")
			{
				//Instancio el componente
				$this->lector=new Reader;
				
				//variables para validaciones
				$is=false;
				$this->lector->define("Ruta Internal Hora");
				//Defino la ruta del archivo en el servidor
				$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR."Ruta Internal Hora.xls";
				//Verifico la existencia del archivo
				if(!file_exists($ruta))
				{
					//Si la extension en minuscula no funciona prueba la mayuscula
					$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR."Ruta Internal Hora.XLS";
					if(file_exists($ruta))
					{
						$is=true;
					}
				}
				else
				{
					$is=true;
				}
				if($is)
				{
					//procedo a leerlo
					if($this->lector->hora($ruta))
					{
						//si guardo con exito registro en log
						Log::registrarLog(LogAction::getId("Carga Ruta Internal ".$this->lector->horas."GMT"));
						if(file_exists($ruta))
						{
							unlink($ruta);
						}
					}
					switch($this->lector->error)
					{
						case 0:
							$exitos.="<h5 class='cargados'> El arhivo 'Ruta Internal ".$this->lector->horas."GMT' se guardo con exito </h5> <br/>";
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							break;
						case 1:
							$fallas.="<h5 class='nocargados'> El archivo 'Ruta Internal ".$this->lector->horas."GMT' tiene una estructura incorrecta </h5> <br/> ";
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							break;
						case 2:
							$fallas.="<h5 class='nocargados'> El archivo 'Ruta Internal ".$this->lector->horas."GMT' ya esta almacenado </h5> <br/> ";
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							break;
						case 3:
							$fallas.="<h5 class='nocargados'> El archivo 'Ruta Internal ".$this->lector->horas."GMT' tiene una fecha incorrecta </h5> <br/> ";
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							break;
						case 4:
							$fallas.="<h5 class='nocargados'> El archivo 'Ruta Internal ".$this->lector->horas."GMT' no esta en el servidor </h5> <br/> ";
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							break;
					}
				}
				else
				{
					if(strlen($fallas)<=16)
					{
						$fallas="No hay archivos en el servidor";
					}
				}
			}
			//Si la opcion es rerate
			elseif($_POST['tipo']=="rerate")
			{
				//variables para validacion
				$error=false;
				$fechasArchivos=array();
				$erroresArchivos=array();

				/**
				* saco cuenta de la cantidad de dias en el rango introducido
				*/
				$dias=Utility::dias(Utility::formatDate($_POST['fechaInicio']),Utility::formatDate($_POST['fechaFin']));
				$tiempo=$dias*3200;
				ini_set('max_execution_time', $tiempo);
				/**
				* array con los posibles nombres en el archivo del rerate
				*/
				$archivos=array(
					'Carga Ruta Internal Rerate'=>'Ruta Internal RR',
					'Carga Ruta External Rerate'=>'Ruta External RR'
					);
				
				if($dias>0)
				{
					/**
					* Verifico que los archivos necesarios se encuentren en el servidor
					*/
					foreach($archivos as $key => $archivo)
					{
						for($i=0; $i<=$dias; $i++)
						{
							$j="";
							if($i>0)
							{
								$j=$i;
							}
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".xls";
							if(!file_exists($ruta))
							{
								//Si no existe la cambio
								$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".XLS";
								if(file_exists($ruta))
								{
									$exitos.="<h5 class='cargados'> El arhivo '".$archivo.$j."' esta en el servidor </h5> <br/>";
									$error=false;
								}
								else
								{
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' No esta en el servidor </h5> <br/> ";
									$error=true;
								}
							}
							else
							{
								$exitos.="<h5 class='cargados'> El arhivo '".$archivo.$j."' esta en el servidor </h5> <br/>";
								$error=false;
							}
						}
						/**
						* creo los arrays con las fechas indicadas
						*/
						$fechas=array();
						for($i=0;$i<$dias;$i++)
						{
							$nuevafecha=strtotime('+'.$i.' day',strtotime(Utility::formatDate($_POST['fechaInicio'])));
							$nuevafecha=date('Y-m-d',$nuevafecha);
							$fechas[$nuevafecha]=false;
						}
						$fechasArchivos[$archivo]=$fechas;
					}
				}
				if(!$error)
				{
					//inicializo la variable que contiene los errores
					$cuentaFechas="";
					//funcion para verificar el valor false
					function falsa($var)
					{
						return($var==false);
					}
					//importo la extension de lectura de archivos
					Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
					/**
					* Verifico si la fecha es la correcta en el archivo
					*/
					//primero extraigo las fechas
					foreach($archivos as $key => $archivo)
					{
						for($i=0; $i<=$dias; $i++)
						{
							$j="";
							if($i>0)
							{
								$j=$i;
							}
							$data = new Spreadsheet_Excel_Reader();
							$data->setOutputEncoding('ISO-8859-1');
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".xls";
							if(!file_exists($ruta))
							{
								$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".XLS";
							}
							$data->read($ruta);
							$fechasArchivos[$archivo][Utility::formatDate($data->sheets[0]['cells'][1][4])]=true;
							unset($data);
						}
					}
					//Reviso si alguna de las fechas ya creadas tiene false
					foreach($archivos as $key => $archivo)
					{
						$valoresFalse=array_filter($fechasArchivos[$archivo],'falsa');
						if(count($valoresFalse)>=1)
						{
							foreach($fechasArchivos[$archivo] as $fecha => $value)
							{
								if(!$value)
								{
									$cuentaFechas.=" ".$fecha." del archivo ".$archivo.",";
									$error=true;
								}
							}
							$fallas.="<h5 class='nocargados'> Faltan las fechas '".$cuentaFechas."'</h5> <br/> ";
						}
					}
				}
				else
				{
					//Elimino los archivos
					foreach($archivos as $key => $archivo)
					{
						for($i=0; $i<=$dias; $i++)
						{
							$j="";
							if($i>0)
							{
								$j=$i;
							}
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".xls";
							if(!file_exists($ruta))
							{
								$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".XLS";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
							else
							{
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
						}
					}
				}

				if(!$error)
				{
					//Instancio el componente
					$this->lector=new Reader;
                    Log::registrarLog(LogAction::getLikeId('Rerate Iniciado'));
					foreach($archivos as $key => $archivo)
					{
						$this->lector->define($archivo);
						for($i=0; $i<=$dias; $i++)
						{
							$j="";
							if($i>0)
							{
								$j=$i;
							}
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".xls";
							if(!file_exists($ruta))
							{
								$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".XLS";
							}
							if($this->lector->rerate($ruta,$key))
							{
								//si guardo con exito registro en log
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
							switch($this->lector->error)
							{
								case 0:
									$exitos.="<h5 class='cargados'> El arhivo '".$archivo.$j."' se guardo con exito </h5> <br/>";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=true;
									break;
								case 1:
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' tiene una estructura incorrecta </h5> <br/> ";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=false;
									break;
								case 2:
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' ya esta almacenado </h5> <br/> ";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=false;
									break;
								case 3:
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' tiene una fecha incorrecta </h5> <br/> ";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=false;
									break;
								case 4:
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' no esta en el servidor </h5> <br/> ";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=false;
									break;
								case 6:
									$fallas.="<h5 class='nocargados'> El archivo '".$archivo.$j."' grabo en base de datos pero fall� el log</h5><br>";
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
									$erroresArchivos[$archivo.$j]=false;
									break;
							}
						}
					}
					$NumErrores=array_filter($erroresArchivos,'falsa');
					if($NumErrores>=$dias*2)
					{
						Log::registrarLog(LogAction::getId('Rerate'));
					}
				}
				else
				{
					//Elimino los archivos
					foreach($archivos as $key => $archivo)
					{
						for($i=0; $i<=$dias; $i++)
						{
							$j="";
							if($i>0)
							{
								$j=$i;
							}
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".xls";
							if(!file_exists($ruta))
							{
								$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$archivo.$j.".XLS";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
							else
							{
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
						}
					}
				}
			}
		}
		$resultado.=$exitos."</br>".$fallas."</div>";
       	$this->render('guardar',array('data'=>$resultado));
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
}
