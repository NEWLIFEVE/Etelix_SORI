<?php

class GeographicZoneController extends Controller
{
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
				'actions'=>array('index','create','view','DynamicAsignados','ElijeTipoDestination','UpdateZonaDestino','CreateZoneColor','_geographicZoneAdmin','GuardarZoneColor','UpdateZoneColor','buscaColor'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','DynamicAsignados','ElijeTipoDestination','UpdateZonaDestino'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','DynamicAsignados','ElijeTipoDestination','UpdateZonaDestino'),
				'users'=>array('admin'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new GeographicZone;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['GeographicZone']))
		{
			$model->attributes=$_POST['GeographicZone'];
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

		if(isset($_POST['GeographicZone']))
		{
			$model->attributes=$_POST['GeographicZone'];
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
		$dataProvider=new CActiveDataProvider('GeographicZone');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GeographicZone('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GeographicZone']))
			$model->attributes=$_GET['GeographicZone'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GeographicZone the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GeographicZone::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GeographicZone $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='geographic-zone-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        /**
	 * este busca los destinos asignados a zonas geograficas
	 */
        public function actionDynamicAsignados()
        {
    //        echo CHtml::tag('option',array('value'=>'empty'),'Seleccione uno',true);
            
            $tipoDestino=$_POST['destinos'];
             if ($tipoDestino==1)
               {
                  $data = Destination::getListDestinationAsignados($_POST['GeographicZone']);
               }
               else if ($tipoDestino==2)
               {
                   $data = DestinationInt::getListDestinationIntAsignados($_POST['GeographicZone']);  
               }
           
            foreach($data as $value=>$name)
            {
                echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
            }
        } 
        /**
	 *  recibe el valor de id_destination
         *  dependiendo de si es 1 o dos
         *  va a ejecutar una de las consultas al modelo
         *  destination o destinationInt respectivamente
	 */
        public function actionElijeTipoDestination()
        {
            $tipoDestino=$_POST['GeographicZone']['id_destination'];
            
           if ($tipoDestino==1)
               {
                 $data = Destination::getListDestinationNoAsig();
               }
               else if ($tipoDestino==2)
               {
                  $data = DestinationInt::getListDestinationIntNoAsig();  
               }
            foreach($data as $value=>$name)
            {
                echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
            }
        } 
        /**
         *envia a la vista de nueva zona geograica
	 */
        public function actionCreateZoneColor()
	{
            
            $model=new GeographicZone;

		if(isset($_POST['GeographicZone']))
		{
			$model->attributes=$_POST['GeographicZone'];
			if($model->save())
				$this->render('_geographicZoneAdmin',array('model'=>$model));
		}

		$this->render('_zonaGeo_color',array(
			'model'=>$model,
		));
	}
        /**
	 *  recibe el valor de name_zona y color_zona
         *  va a ejecutar una de las consultas al modelo
         * guarda una nueva zona geografica  y devuelve a views.js
	 */
        public function actionGuardarZoneColor()
	{
             $name_zona = $_GET['name_zona'];
             $color_zona = $_GET['color_zona'];
            
            $model=new GeographicZone;
            $model->name_zona = $name_zona;
            $model->color_zona = $color_zona;

            if($model->save())	
            {
                $params['name_zonaG'] =$model->name_zona;
                $params['color_zonaG'] = $model->color_zona;

                echo json_encode($params); 
            }
        }
        /**
	 *  recibe el valor de name_zonaSelect y color_zona
         *  va a ejecutar una de las consultas al modelo
         * actualiza la zona geografica y devuelve a views.js
	 */
        public function actionUpdateZoneColor()
	{
             $name_zonaSelect = $_GET['name_zonaSelect'];
             $color_zona = $_GET['color_zona'];
             $name_zonaG=GeographicZone::getName($name_zonaSelect);
               $model=$this->loadModel($name_zonaSelect);
               $model->color_zona = $color_zona;

            if($model->save())	
           {
               $params['name_zonaG'] =$name_zonaG;
               $params['color_zonaG'] = $model->color_zona;
               echo json_encode($params); 
           }	
        }
         /**
	 *  recibe el valor de id_zonaSelect 
         *  va a ejecutar una de las consultas al modelo
         * trae el color al input en la vista, dependiendo del id
	 */
        public function actionbuscaColor()
        {
           $id_zonaSelect = $_GET['id_zonaSelect']; 
           $color_zona=GeographicZone::getColor($id_zonaSelect);
           echo $color_zona;
        }
}
