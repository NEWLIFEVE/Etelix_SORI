<?php

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
		//capturo el nombre del usuario logueado
        $user_carpeta_temp=Yii::app()->user->getState('username').'';
        
		//Cada vez que el usuario llegue al upload se verificaran si hay archivos en la carpeta uploads y se eliminaran
//		$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR;
		$ruta=Yii::getPathOfAlias('webroot')."/uploads/".$user_carpeta_temp."/";
        if(file_exists($ruta)){
		
        }else{
        $ruta=Yii::getPathOfAlias('webroot')."/uploads/";
        }
      
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
		
			$ruta2=Yii::getPathOfAlias('webroot')."/uploads/".$user_carpeta_temp."/";
			if($ruta==$ruta2){
			 //al borrar todos loos archivos de la carpeta procedo a borrar la carpeta
             rmdir(Yii::getPathOfAlias('webroot')."/uploads/".$user_carpeta_temp."/");
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

		//Primero: verifico que archivos estÃ¡n
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
		
		//capturo el nombre del usuario logueado
        $user_carpeta_temp=Yii::app()->user->getState('username').'/';
        $ruta='uploads/'.$user_carpeta_temp.'/';
        
        if(file_exists($ruta)){
         //no la crea solo sube el siguiente archivo a la misma carpeta
        }else{
		 //creo el directorio dependiendo del usuario logueado, sino existe la carpeta
         mkdir("uploads/".$user_carpeta_temp."", 0775);
        }
        //concateno la carpeta temp para la carga
		$folder='uploads/'.$user_carpeta_temp.'/';// folder for uploaded files
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
		$user_carpeta_temp=Yii::app()->user->getState('username').'';
		 
		$path=Yii::getPathOfAlias('webroot')."/uploads/".$user_carpeta_temp."/";
		//$path=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR;
		
		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";

        //Verfico si el arreglo post esta seteado
		if(isset($_POST['tipo']))
		{
			//Verifico la opcion del usuario a traves del post
			//si la opcion es dia
			if($_POST['tipo']=="dia")
			{
				//instancio el componente para validar
				$this->valida=new ValidationsArchCapt; 
				$this->lector=new Reader;
				
				//Nombres opcionales para los archivos diarios
				$diarios=array(
					'Carga Ruta Internal'=>'Ruta Internal Diario',
					'Carga Ruta External'=>'Ruta External Diario'
					);
					//Primero: verifico que archivos estÃ¡n
				$existentes=$this->valida->getNombreArchivos($path,$diarios,array('xls','XLS'));
				//Si la primera condicion se cumple, no deberian haber errores
				if($this->valida->error==0)
				{
					foreach($existentes as $key => $diario)
					{
						//validaciones 
						if($this->valida->validar($path,$diario,$existentes,$yesterday))
					    {
						   /* guardo en el componente reader*/
							if($this->valida->error==0)
							{
								//Guardo en base de datos
								if($this->lector->diario($path.$diario,$yesterday,$diario))
								{
									//Si lo guarda grabo en log
									Log::registrarLog(LogAction::getId($this->valida->log));
								}
							}
						}
					 }
					   //vañlidar error cero si viene del lector o vañlida
					    if(($this->lector->error>0)||($this->valida->error!=0))
						{
							$fallas.=$this->lector->errorComment;
							$fallas.=$this->valida->errorComment;
						}
						if(($this->lector->error==0)&&($this->valida->error==0))
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$diario."' se guardo con exito </h5> <br/>";
						}
						$this->valida->error=0;
						$this->valida->errorComment=NULL;
					}
				}
				if(($this->lector->error>0)||($this->valida->error!=0))
				{
					$fallas.=$this->lector->errorComment;
				    $fallas.=$this->valida->errorComment;
				}
			}
		$resultado.=$exitos."</br>".$fallas."</div>";
       	$this->render('guardar',array('data'=>$resultado));
	}
}
