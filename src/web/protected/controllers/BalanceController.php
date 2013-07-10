<?php

class BalanceController extends Controller
{
	/*
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','ventas','compras', 'guardar', 'ver'),
				'users'=>array_merge(Users::usersByType(1)),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
    public function actionCompras()
	{
		$model=new Balance;
		$this->render('compras',array('model'=>$model));
	}
	public function actionVentas()
	{
		$model=new Balance;
		$this->render('ventas',array('model'=>$model));
	}
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
		//html preparado para mostrar resultados
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
		$exitos="<h3> Exitos</h3>";
        $fallas="<h3> Fallas</h3>";
        $siguiente=false;
        //Verfico si el arreglo post esta seteado
		if(isset($_POST['tipo']))
		{
			//Verifico la opcion del usuario a través del post
			//si la opcion es dia
			if($_POST['tipo']=="dia")
			{
				//instancio el componente
				$this->lector=new Reader;
				//Nombres opcionales para los archivos diarios
				$diarios=array(
					'Carga Venta Internal'=>'VentaInternal',
					'Carga Venta External'=>'VentaExternal',
					'Carga Compra Internal'=>'CompraInternal',
					'Carga Compra External'=>'CompraExternal'
					);
				//recorro el array de nombres
				foreach($diarios as $key => $diario)
				{
					//variables para validaciones
					$is=false;
					$log=false;
					$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".xls";
					$this->lector->define($diario);
					//Verifico la existencia del archivo
					if(!file_exists($ruta))
					{
						$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".XLS";
						if(file_exists($ruta))
						{
							$is=true;
						}
					}
					else
					{
						$is=true;
					}
					//verifico que no este en el log
					if($is)
					{
						if(Log::existe(LogAction::getId($key)))
						{
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							$log=true;
						}				
					}
					if($is==true && $log==false)
					{
						if($this->lector->diario($ruta))
						{
							Log::registrarLog(LogAction::getId($key));
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							$siguiente=true;
						}
						if($this->lector->error==0)
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$diario."' se guardo con exito </h5> <br/>";
						}
						elseif($this->lector->error==1)
						{
							$fallas.="<h5 class='nocargados'> El archivo '".$diario."' tiene una estructura incorrecta </h5> <br/> ";
						}
						elseif($this->lector->error==2)
						{
							$fallas.="<h5 class='nocargados'> El archivo '".$diario."' ya esta almacenado </h5> <br/> ";
						}
						elseif($this->lector->error==3)
						{
							$fallas.="<h5 class='nocargados'> El archivo '".$diario."' tiene una fecha incorrecta </h5> <br/> ";
						}
						elseif($this->lector->error==4)
						{
							$fallas.="<h5 class='nocargados'> El archivo '".$diario."' no esta en el servidor </h5> <br/> ";
						}
					}
				}
			}
			//Si la opcion es hora
			elseif($_POST['tipo']=="hora")
			{
				//Instancio el componente
				$this->lector=new Reader;
				//array con los posibles nombres en el archivo horas
				$horarios=array(
					'Carga Venta Internal'=>'VentaInternalHora',
					'Carga Compra Internal'=>'CompraInternalHora'
					);
				//Recorro los nombres en array
				foreach($horarios as $key => $hora)
				{
					//variables para validaciones
					$is=false;
					$this->lector->define($hora);
					//Defino la ruta del archivo en el servidor
					$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$hora.".xls";
					//Verifico la existencia del archivo
					if(!file_exists($ruta))
					{
						//Si la extension en minuscula no funciona prueba la mayuscula
						$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$hora.".XLS";
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
						if($this->lector->hora($ruta,$key))
						{
							//si guardo con exito registro en log
							Log::registrarLog(LogAction::getLikeId($key."%".$this->lector->horas."%"));
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
							$siguiente=true;
						}
						switch($this->lector->error)
						{
							case 0:
								$exitos.="<h5 class='cargados'> El arhivo '".$hora." ".$this->lector->horas."' se guardo con exito </h5> <br/>";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
								break;
							case 1:
								$fallas.="<h5 class='nocargados'> El archivo '".$hora." ".$this->lector->horas."' tiene una estructura incorrecta </h5> <br/> ";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
								break;
							case 2:
								$fallas.="<h5 class='nocargados'> El archivo '".$hora." ".$this->lector->horas."' ya esta almacenado </h5> <br/> ";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
								break;
							case 3:
								$fallas.="<h5 class='nocargados'> El archivo '".$hora." ".$this->lector->horas."' tiene una fecha incorrecta </h5> <br/> ";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
								break;
							case 4:
								$fallas.="<h5 class='nocargados'> El archivo '".$hora." ".$this->lector->horas."' no esta en el servidor </h5> <br/> ";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
								break;
							default:
								# code...
								break;
						}
					}
					else
					{
						if(strlen($fallas)<=16 && $siguiente==false)
						{
							$fallas="No hay archivos en el servidor";
						}
					}
				}//fin foreach
			}
			//Si la opcion es rerate
			elseif($_POST['tipo']=="rerate")
			{
				foreach($diarios as $key => $rerate)
				{
					//Defino la ruta
					$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".xls";
					if(!file_exists($ruta))
					{
						//Si no existe la cambio
						$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".XLS";
					}
				}
			}
		}
		$resultado.=$exitos."</br>".$fallas."</div>";
        if($siguiente)
        {
        	$this->render('guardar',array('data'=>$resultado));
        }
        else
        {
        	Yii::app()->user->setFlash('error', $fallas);
			$this->redirect('/site/');
        }
	}
	public function actionVer()
	{
		$this->render('guardar',array('data'=>$this->nombre));
	}
}
