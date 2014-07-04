<?php

class DestinationIntController extends Controller
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
				'actions'=>array('index','view','UpdateZonaDestinationInt','BuscaNombresDesInt'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$model=new DestinationInt;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DestinationInt']))
		{
			$model->attributes=$_POST['DestinationInt'];
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

		if(isset($_POST['DestinationInt']))
		{
			$model->attributes=$_POST['DestinationInt'];
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
		$dataProvider=new CActiveDataProvider('DestinationInt');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DestinationInt('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DestinationInt']))
			$model->attributes=$_GET['DestinationInt'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DestinationInt the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DestinationInt::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DestinationInt $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='destination-int-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        public function actionBuscaNombresDesInt(){
        
            $GeographicZone = $_GET['GeographicZone'];
            $asignados = explode(',', $_GET['asignados']); // convierto el string a un array.
            $noasignados = explode(',', $_GET['noasignados']); // convierto el string a un array.  
            $asigNames="";
            $noasigNames="";
            $GeographicZoneName="";
            $GeographicZoneName.= GeographicZone::getName($GeographicZone);
            foreach ($asignados as $key => $value) {
                $modelAsig = DestinationInt::model()->findByPk($asignados[$key]);
                 if ($modelAsig->id_geographic_zone != $GeographicZone)
                    $asigNames.= $modelAsig->name.", ";      
            }
            foreach ($noasignados as $key => $value) {
                $modelNoAsig = DestinationInt::model()->findByPk($noasignados[$key]);
                if ($modelNoAsig->id_geographic_zone != 2) 
                    $noasigNames.=$modelNoAsig->name.", ";
            }
                    $params['GeographicZoneName']=$GeographicZoneName;    
                    $params['asigNames']=$asigNames;    
                    $params['noasigNames']=$noasigNames;    
                       echo json_encode($params);
             
        }
        public function actionUpdateZonaDestinationInt(){
        
            $GeographicZone = $_GET['GeographicZone'];
            $asignados = explode(',', $_GET['asignados']); // convierto el string a un array.
            $noasignados = explode(',', $_GET['noasignados']); // convierto el string a un array.  
            $asigNames="";
            $noasigNames="";

            foreach ($asignados as $key => $value) {
                $modelAsig = DestinationInt::model()->findByPk($asignados[$key]);
                 if ($modelAsig->id_geographic_zone != $GeographicZone)
                {   
                $modelAsig->id_geographic_zone = $GeographicZone;
                if($modelAsig->save()){                
                    $asigNames.= $modelAsig->name.", ";
                } 
                }         
            }
            foreach ($noasignados as $key => $value) {
                $modelNoAsig = DestinationInt::model()->findByPk($noasignados[$key]);
                if ($modelNoAsig->id_geographic_zone != 2)
                {
                $modelNoAsig->id_geographic_zone = 2;
                if($modelNoAsig->save() ){
                    $noasigNames.=$modelNoAsig->name.", ";
                }
                }
            }  
        }
    
}
