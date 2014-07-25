<?php
/**
 *
 */
class BalanceController extends Controller
{
	/**
	 * Atributo para instanciar el componente reader
	 */
	public $lector;
	private $nombre;
	public $valida;
    public $error=0;
    public $errorComment;
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
				'actions'=>array('uploadtemp','cargatemp','guardartemp','index','view','admin','delete','create','update','ventas','compras','carga', 'guardar', 'ver', 'memoria','upload','delete','disabledDaily','fecha'),
				'users'=>array_merge(Users::usersByType(1)),
				),
			array('allow', // Vistas para NOC
				'actions'=>array('index','guardar','upload','carga','disabledDaily'),
				'users'=>array_merge(Users::usersByType(2)),
				),
			array('allow', // Vistas para Operaciones
				'actions'=>array('index','view','admin','delete','create','update','ventas','compras', 'guardar', 'ver', 'memoria','upload','delete','disabledDaily'),
				'users'=>array_merge(Users::usersByType(3)),
				),
			array('allow', // Vistas para Operaciones
				'actions'=>array('index','view','admin','delete','create','update','ventas','compras', 'guardar', 'ver', 'memoria','upload','delete','disabledDaily'),
				'users'=>array_merge(Users::usersByType(6)),
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
		//capturo el nombre del usuario logueado
        $userTemporaryFolder=Yii::app()->user->getState('username').'';
        
		//Cada vez que el usuario llegue al upload se verificaran si hay archivos en la carpeta uploads y se eliminaran
		$ruta=Yii::getPathOfAlias('webroot.uploads.'.$userTemporaryFolder).DIRECTORY_SEPARATOR;
		$archivos=0;
		if(is_dir($ruta))
		{
			$archivos=@scandir($ruta);
		}
		else
		{
			mkdir("uploads/".$userTemporaryFolder."/", 0775, true);
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
		
			$ruta2=Yii::getPathOfAlias('webroot')."/uploads/".$userTemporaryFolder."/";
			if($ruta==$ruta2){
			 //al borrar todos loos archivos de la carpeta procedo a borrar la carpeta
             rmdir(Yii::getPathOfAlias('webroot')."/uploads/".$userTemporaryFolder."/");
			}
       
		}
		$this->render('upload');               
	}

	/**
	 *
	 */
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
	/**
	 *
	 */
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
	/**
	 *
	 */
	public function actionGuardartemp()
	{
		ini_set('max_execution_time', 2000);
        ini_set('memory_limit', -1);
		//capturo el nombre del usuario logueado
		$userTemporaryFolder=Yii::app()->user->getState('username').'';
		$path=Yii::getPathOfAlias('webroot')."/uploads/temp/";
		
		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";

        //Verfico si el arreglo post esta seteado
		if(isset($_POST['tipo']))
		{
			$tipo=$_POST['tipo'];

			if($tipo=='dia')
			{
				//Nombres opcionales para los archivos diarios
				$namesArch=array(
					'Carga Ruta Internal'=>'Ruta Internal Diario',
					'Carga Ruta External'=>'Ruta External Diario'
					);
			}
			//Primero: verifico que archivos estan
		  	$existentes=ValidationsArchCapt::getNombreArchivos($path,$namesArch,array('xls','XLS'));
		  	$countExistentes=0;
		  	//Si la primera condicion se cumple, no deberian haber errores
		  	if($this->error==ValidationsArchCapt::ERROR_NONE)
		  	{
		  		$nombres=array();
			 	$nombreArc="";
				foreach($existentes as $key => $nombre)
			    {
			    	$countExistentes=$countExistentes+1;	
				 	//cargo el archivo en memoria
				 	$ruta=$path.$nombre;
				 	$archivo=new Reader($ruta);

				 	// se toma la fecha del archivo. 
			 		$date=Utility::formatDate($archivo->excel->sheets[0]['cells'][1][4]);

			   		if($this->error==ValidationsArchCapt::ERROR_NONE)
				 	{
			   			// genero un array con los datos del excel para guardarlo en BD y saber si es interno o externo
				 		$var=Reader::diario($date, $nombre, $archivo);
				   		if($var!=null) 
				   		{
			                //genero un string con los datos premilinares external o internal antes de insertar los nuevos y borrar los actuales
				     		$stringDataPreliminary=ValidationsArchCapt::loadArchTemp($date,$var,$tipo,$archivo,null);

				   			if(ValidationsArchCapt::saveDataArchDayHours($var,$tipo)) 
				   			{
				   				if($stringDataPreliminary!=" ")
				   				{
						   			ValidationsArchCapt::deleteArchTempDayHours($stringDataPreliminary,$tipo);
				   				}
				   			}
				    	}
					}
				    $nombres[]=$nombre;
		    		$nombreArc=implode(",",  $nombres); 
				}

				if($this->error!=ValidationsArchCapt::$error)
				{
					$fallas.=ValidationsArchCapt::$errorComment;
				}
				if($this->error==ValidationsArchCapt::$error)
				{

						if($countExistentes==1)
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$nombreArc."' se guardo con exito </h5> <br/>";	 	
						}
						elseif($countExistentes>=1)
						{
							$exitos.="<h5 class='cargados'> Los archivos '".$nombreArc."' se guardaron con exito </h5> <br/>";	
						}     	
				}
			 
				$this->error=ValidationsArchCapt::ERROR_NONE;
				$this->errorComment=NULL;
			}
		   	/********* resultado de la carga*************/
			$resultado.=$exitos."</br>".$fallas."</div>";
		   	$this->render('guardar',array('data'=>$resultado, 'fechas'=>$yesterday));
		   	/********* resultado de la carga*************/
		}	
	}//fin actionGuardar

	
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

		//capturo el nombre del usuario logueado
        $userTemporaryFolder=Yii::app()->user->getState('username').'/';
        $ruta='uploads/'.$userTemporaryFolder.'/';
        
        if(!file_exists($ruta))
        {
        	//creo el directorio dependiendo del usuario logueado, sino existe la carpeta
         	mkdir("uploads/".$userTemporaryFolder."", 0775);
        }
        //concateno la carpeta temp para la carga
		$folder='uploads/'.$userTemporaryFolder.'/';// folder for uploaded files
//		$folder='uploads/';// folder for uploaded files

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
		$date=date('Y-m-d');
		$yesterday=strtotime('-1 day',strtotime($date));
		$yesterday=date('Y-m-d',$yesterday);

		//capturo el nombre del usuario logueado
		$userTemporaryFolder=Yii::app()->user->getState('username').'';

		$path=Yii::getPathOfAlias('webroot')."/uploads/".$userTemporaryFolder."/";

		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";

        //Verfico si el arreglo post esta seteado
		if(isset($_POST['tipo']))
		{
			$tipo=$_POST['tipo'];

			if($tipo=='dia')
			{
				//Nombres opcionales para los archivos diarios
				$namesArch=array(
					'Carga Ruta Internal'=>'Ruta Internal Diario',
					'Carga Ruta External'=>'Ruta External Diario'
					);
			}
			elseif($tipo=='hora')
			{
				//Nombres opcionales para los archivos horas
 		    	$namesArch=array(
 		    		'Carga Ruta Internal 3GMT'=>'Ruta Internal 3Hrs',
 		    		'Carga Ruta Internal 7GMT'=>'Ruta Internal 7Hrs',
 		    		'Carga Ruta Internal 11GMT'=>'Ruta Internal 11Hrs',
					'Carga Ruta Internal 15GMT'=>'Ruta Internal 15Hrs',
					'Carga Ruta Internal 19GMT'=>'Ruta Internal 19Hrs',
					'Carga Ruta Internal 23GMT'=>'Ruta Internal 23Hrs',
					'Carga Ruta External 4GMT'=>'Ruta External 4Hrs',
					'Carga Ruta External 8GMT'=>'Ruta External 8Hrs',
					'Carga Ruta External 12GMT'=>'Ruta External 12Hrs',
					'Carga Ruta External 16GMT'=>'Ruta External 16Hrs',
					'Carga Ruta External 20GMT'=>'Ruta External 20Hrs',
					'Carga Ruta External 24GMT'=>'Ruta External 23Hrs'
					);
			}
			//Primero: verifico que archivos estan
		  	$existentes=ValidationsArchCapt::getNombreArchivos($path,$namesArch,array('xls','XLS'));
		  	$countExistentes=0;
		  	//Si la primera condicion se cumple, no deberian haber errores
		  	if($this->error==ValidationsArchCapt::ERROR_NONE)
		  	{
		  		$nombres=array();
			 	$nombreArc="";
				foreach($existentes as $key => $nombre)
			    {
			    	$countExistentes=$countExistentes+1;	
				 	//cargo el archivo en memoria
				 	$ruta=$path.$nombre;
				 	$archivo=new Reader($ruta);
				 	if($tipo=='hora')
				 	{
				 		$total=$archivo->excel->sheets[0]['numRows']-1;
				 		$ultima=$archivo->excel->sheets[0]['cells'][$total][1];
				 	}
				 	       				
				   	if(ValidationsArchCapt::validar($path,$nombre,$existentes,$yesterday,$archivo,$tipo))
				   	{
				   		if($this->error==ValidationsArchCapt::ERROR_NONE)
					 	{
					   		$var=array();
	                   		if($tipo=='dia')
					   		{
					   			// genero un array con los datos del excel para guardarlo en BD y saber si es interno o externo
						 		$var=Reader::diario($yesterday, $nombre, $archivo);
					   		}
					   		elseif($tipo=='hora')
							{
						 		//genero un string con los datos cargados del dia para luego borrarlos y agregar los actualizados	
						 		$var=Reader::hora($archivo,$nombre,$ultima);
					    	}

					   		if($var!=null) 
					   		{
		                 		//Si se genero el string nuevo, guardo el log
					     		if (ValidationsArchCapt::logDayHours($nombre,$tipo))
					     		{	 
					                //genero un string con los datos premilinares external o internal antes de insertar los nuevos y borrar los actuales
						     		$stringDataPreliminary= ValidationsArchCapt::loadArchTemp($yesterday,$var,$tipo,$archivo,$var['hora']);	

						 		   //guardo en BD el string con los nuevos datos del excel diario u Hora
						   			if(ValidationsArchCapt::saveDataArchDayHours($var,$tipo)) 
						   			{
							   			if($tipo=='dia')
						 	     		{
								    		Log::registrarLog(LogAction::getId(ValidationsArchCapt::logDayHours($nombre,$tipo)));
							     		}
							     		elseif($tipo=='hora')
							      		{
							        		$numero = explode("Hrs", $nombre);
				     						$numero = explode(" ", $numero[0]);
				   							//$nombre="Carga Ruta ".$numero[1]." ".$numero[2]."Hrs";
				   							$nombre="Carga Ruta ".$numero[1]." ".$var['hora']."Hrs";
				   							$nombre2="Ruta ".$numero[1]." ".$numero[2]."Hrs";
				   							//echo $nombre;
								    		Log::registrarLog(LogAction::getId($nombre));
										}
							   			if($stringDataPreliminary!="") 
							   			{
							   				ValidationsArchCapt::deleteArchTempDayHours($stringDataPreliminary,$tipo);	
							   			}
						   			}
					     		}
					    	}
						}
					}

					if($tipo=='dia')
					{
					    $nombres[]=$nombre;
			    		$nombreArc=implode(",",  $nombres); 
					}
					elseif($tipo=='hora')
					{
						$nombres[]=$nombre2;
					    $nombreArc=implode(" , ",  $nombres); 
					}
				}

				if($this->error!=ValidationsArchCapt::$error)
				{
					$fallas.=ValidationsArchCapt::$errorComment;
				}
				if($this->error==ValidationsArchCapt::$error)
				{
					if($tipo=='dia')
					{
						if($countExistentes==1)
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$nombreArc."' se guardo con exito </h5> <br/>";	 	
						}
						elseif($countExistentes>=1)
						{
							$exitos.="<h5 class='cargados'> Los archivos '".$nombreArc."' se guardaron con exito </h5> <br/>";	
						}     	
					}
				  	elseif($tipo=='hora')
				  	{
				  		if($countExistentes==1)
				  		{
				  			$exitos.="<h5 class='cargados'> El arhivo '".$nombreArc."' se guardo con exito </h5> <br/>";
				  	    }
				  	    elseif($countExistentes>=1)
				  	    {
							$exitos.="<h5 class='cargados'> Los archivos '".$nombreArc."' se guardaron con exito </h5> <br/>";	
						}
					}
				}

				$this->error=ValidationsArchCapt::ERROR_NONE;
				$this->errorComment=NULL;
			}
		   	/********* resultado de la carga*************/
			$resultado.=$exitos."</br>".$fallas."</div>";
		   	$this->render('guardar',array('data'=>$resultado, 'fechas'=>$yesterday));
		   	/********* resultado de la carga*************/
		}	
	

	}//fin actionGuardar

	/**
	 *
	 */
  	public function actionDisableddaily()
	{
	 	$fecha = $_POST['fecha'];
	    $resultado=array();
		$model=Log::model()->count("date=:fecha AND id_log_action>=1 AND id_log_action<=4", array(':fecha'=>$fecha));
		if($model>=4)
		{
			//ya se cargaron los 4 archivos diarios
			$resultado['error'] = "si";
		}
		else
		{
			$resultado['error'] = "no";
		}
		echo json_encode($resultado);
	}
}
