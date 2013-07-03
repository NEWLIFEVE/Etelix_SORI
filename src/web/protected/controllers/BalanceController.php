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
	public function actionGuardar()
	{
   		$diarios=array(
   			'Carga Venta Internal'=>'VentaInternal',
   			'Carga Venta External'=>'VentaExternal',
   			'Carga Compra Internal'=>'CompraInternal',
   			'Carga Compra External'=>'CompraExternal'
   			);
		$rerates=array(1=>'ruta venta internal rr', 2=>'ruta venta rr', 3=>'ruta compra internal rr', 4=>'ruta compra rr');
		$todos=array(1=>'VentaInternal', 2=>'VentaExternal', 3=>'CompraInternal', 4=>'CompraExternal',5=>'ruta venta internal hora', 6=>'ruta venta hora', 7=>'ruta compra internal hora', 8=>'ruta compra hora',9=>'ruta venta internal rr', 10=>'ruta venta rr', 11=>'ruta compra internal rr', 12=>'ruta compra rr');
		$resultado="<h2> Resultados de Carga</h2><div class='detallecarga'>";
                $exitos="<h3> Exitos</h3>";
                $fallas="<h3> Fallas</h3>";
		if(isset($_POST['tipo']))
		{
			if($_POST['tipo']=="dia")
			{
				foreach($diarios as $key => $diario)
				{
					$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".xls";
					$this->lector=new Reader;
					$this->lector->define($diario);
					if(!file_exists($ruta))
					{
						$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".XLS";
					}
					if(Log::existe(LogAction::getId($key)))
					{
						$fallas.="<h5 class='nocargados'> El archivo '".$diario."' ya fue cargado en base de datos </h5> <br/> ";
						if(file_exists($ruta))
						{
							unlink($ruta);
						}
					}
					else
					{
						if($this->lector->diario($ruta))
						{
							Log::registrarLog(LogAction::getId($key));
							if(file_exists($ruta))
							{
								unlink($ruta);
							}
						}
						if($this->lector->error==0)
						{
							$exitos.="<h5 class='cargados'> El arhivo '".$diario."' se guardo con exito </h5> <br/>";
						}
						elseif($this->lector->error==1)
						{

							$fallas.="<h5 class='nocargados'> El archivo '".$diario."' tiene una fecha incorrecta </h5> <br/> ";

						}
					}
				}
				$variable="diarios";
			}
			elseif($_POST['tipo']=="hora")
			{
				/**
				* Recorro los nombres en array y agregando los numeros
				*/
				$this->lector=new Reader;
				foreach($diarios as $key => $diario)
				{
					$this->lector->define($diario);
					for ($i=0; $i <= 23 ; $i++)
					{ 
						//Defino la ruta
						$ruta=Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.$i.".xls";
						if(!file_exists($ruta))
						{
							//Si no existe la cambio
							$ruta = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.$i.".XLS";
						}
						if(file_exists($ruta))
						{
							//antes de leer el archivo verifico si ya no se cargo antes
							if(Log::existe(LogAction::getLikeId($key."%".$i."%")))
							{
								//si ya se guardo antes
								$fallas.="<h5 class='nocargados'> El archivo '".$diario." ".$i."GMT' ya fue cargado en base de datos </h5> <br/> ";
								if(file_exists($ruta))
								{
									unlink($ruta);
								}
							}
							else
							{
								//procedo a leerlo
								if($this->lector->hora($ruta,$i))
								{
									//si guardo con exito registro en log
									Log::registrarLog(LogAction::getLikeId($key."%".$i."%"));
									if(file_exists($ruta))
									{
										unlink($ruta);
									}
								}
								//Verifico si hubo algun tipo de error
								if($this->lector->error==0)
								{
									$exitos.="<h5 class='cargados'> El arhivo '".$diario." ".$i."GMT' se guardo con exito </h5> <br/>";
								}
								elseif($this->lector->error==1)
								{
									$fallas.="<h5 class='nocargados'> El archivo '".$diario." ".$i."GMT' tiene una fecha incorrecta </h5> <br/> ";
									//$fallas.="<h5 class='nocargados'> El archivo '".$diario." ".$i."GMT' tiene una fecha incorrecta </h5> <br/> ";
								}
								elseif ($this->lector->error==4)
								{
									$fallas.="<h5 class='nocargados'> El archivo '".$diario." ".$i."GMT' tiene un orden de horas incorrecto </h5> <br/> ";
								}
							}
						}
					}//fin for
				}//fin foreach
				$variable="horarios";
			}
			elseif($_POST['tipo']=="rerate")
			{
				foreach($rerates as $key => $rerate)
				{
					$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".xls";
					$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".XLS";
					if(file_exists($minuscula))
					{
						$texto.=$rerate." existe, ";
					}
					elseif(file_exists($mayuscula))
					{
						$texto.=$rerate." existe, ";
					}
					else
					{
						$texto.=$rerate." no existe, ";
					}
				}
				$variable="rerates";
			}
		}
		else
		{
			foreach($todos as $key => $var)
			{
				$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$var.".xls";
				$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$var.".XLS";
				if(file_exists($minuscula))
				{
					unlink($minuscula);
				}
				if(file_exists($mayuscula))
				{
					unlink($mayuscula);
				}
			}
			Yii::app()->user->setFlash('error', "Debe escoger una opción.");
			$this->redirect('/site/');
		}

                $resultado.=$exitos."</br>".$fallas."</div>";

		$this->render('guardar',array('data'=>$resultado));
	}
	public function actionVer()
	{
		$this->render('guardar',array('data'=>$this->nombre));
	}
}
