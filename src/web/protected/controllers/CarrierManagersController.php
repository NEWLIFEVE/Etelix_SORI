<?php

class CarrierManagersController extends Controller
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

                     
                        array(  'allow', // Vistas para Administrador
                                'actions'=>array('index','view','create','admin','update','DynamicAsignados', 'DynamicNoAsignados','UpdateDistComercial','DistComercial','BuscaNombres'),
                                'users'=>array_merge(Users::usersByType(1)),
                        ),
                        array(  'allow', // Vistas para Operaciones
                                'actions'=>array('index','view','create','admin','update','DynamicAsignados', 'DynamicNoAsignados','UpdateDistComercial','DistComercial','BuscaNombres'),
                                'users'=>array_merge(Users::usersByType(3)),
                        ),
                        array(  'allow', // Vistas para Finanzas
                                'actions'=>array('index','view','create','admin','update','DynamicAsignados', 'DynamicNoAsignados','UpdateDistComercial','DistComercial','BuscaNombres'),
                                'users'=>array_merge(Users::usersByType(4)),
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
	public function actionDistComercial()
	{
		$model=new CarrierManagers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CarrierManagers']))
		{
                    
			$model->attributes=$_POST['CarrierManagers'];
                        $Asignados = $_POST['Asignados'];
                        $carriers = " ";
                        foreach ($Asignados as $value) {
                            $carriers = $carriers." - ".$value;
                        }
                        //$noAsignados = $_POST['No_Asignados'];
                        
			//if($model->save())
				$this->redirect(array('view','id'=>1,'id_managers'=>$_POST['CarrierManagers']['id_managers'],'id_carrier'=>$carriers));
		}

		$this->render('distComercial',array(
			'model'=>$model,
		));
	}
	public function actionCreate()
	{
		$model=new CarrierManagers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CarrierManagers']))
		{
			$model->attributes=$_POST['CarrierManagers'];
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

		if(isset($_POST['CarrierManagers']))
		{
			$model->attributes=$_POST['CarrierManagers'];
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
		$dataProvider=new CActiveDataProvider('CarrierManagers');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CarrierManagers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CarrierManagers']))
			$model->attributes=$_GET['CarrierManagers'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CarrierManagers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CarrierManagers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CarrierManagers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='carrier-managers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
                
        public function actionDynamicAsignados()
    { 
//        echo CHtml::tag('option',array('value'=>'empty'),'Seleccione uno',true);
        $data = Managers::getListCarriersAsignados($_POST['CarrierManagers']['id_managers']);
        foreach($data as $value=>$name)
        {
            echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
        }
    }        
        public function actionDynamicNoAsignados()
    {         
    
        $data = Managers::getListCarriersNOAsignados();
        foreach($data as $value=>$name)
        {
            echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
        }
    }
    
    public function actionUpdateDistComercial(){
        $manager = $_GET['manager'];
        $asignados = explode(',', $_GET['asignados']); // convierto el string a un array.
        $noasignados = explode(',', $_GET['noasignados']); // convierto el string a un array.          
  
        if ($manager>0){
            
        if (count($asignados)==0){
            
        }else{
         $managerNames=""; 
         $asigNames="";    
         $noasigNames="";  
         $managerNames.= Managers::getName($manager);
        foreach ($asignados as $key => $value) {
            $model = CarrierManagers::checkCarrierManager($manager, $asignados[$key]);
            
            if ($model){
                
            }else{
                $modelAsignar = new CarrierManagers;
                $modelAsignar->start_date = date("Y-m-d");
                $modelAsignar->id_carrier = $asignados[$key];
                $modelAsignar->id_managers = $manager;
                
                $modelDesasignar = CarrierManagers::checkCarrierManager(8, $asignados[$key]);
                $modelDesasignar->end_date = date("Y-m-d");

             if($modelAsignar->save() && $modelDesasignar->save()){                
                $asigNames.= Carrier::getName($asignados[$key]).",";

                }else{
//                    //echo "NOOOOOO PUDO ASIGNAR A: ".$noasignados[$key]." ";
                }
            }
        }

        foreach ($noasignados as $key => $value) {
            $model = CarrierManagers::checkCarrierManager($manager, $noasignados[$key]);
            
            if ($model){
                $modelSinAsignar = new CarrierManagers;
                $model->end_date = date("Y-m-d");
                $modelSinAsignar->start_date = date("Y-m-d");
                $modelSinAsignar->id_carrier = $noasignados[$key];
                $modelSinAsignar->id_managers = 8;
                if($model->save() && $modelSinAsignar->save()){
                    $noasigNames.=Carrier::getName($noasignados[$key]).",";
                }else{
//                    echo "NOOOOOO PUDO DESASIGNAR A: ".$noasignados[$key]." ";
                }  
            }
        }
        echo $managerNames.'/'.$noasigNames.'/'.$asigNames;
                }
        }else{
            echo "Debe seleccionar un Manager";
        }
    }
    public function actionBuscaNombres(){
        $manager = $_GET['manager'];
        $asignados = explode(',', $_GET['asignados']); // convierto el string a un array.
//                if ($asignados=="null"){
//                      echo'asignados es nulo';
////                      $asignados= '6';
////                      $asigNames.=" "; 
//                   }
        $noasignados = explode(',', $_GET['noasignados']); // convierto el string a un array.          
  
        if ($manager>0){
        if (count($asignados)==0){
        }else{
         $managerNames=""; 
         $asigNames="";    
         $noasigNames="";  
         $managerNames.= Managers::getName($manager);
        foreach ($asignados as $key => $value) {
            $model = CarrierManagers::checkCarrierManager($manager, $asignados[$key]);
            
            if ($model){
            }else{
                $modelAsignar = new CarrierManagers;
                $modelAsignar->start_date = date("Y-m-d");
                $modelAsignar->id_carrier = $asignados[$key];
                $modelAsignar->id_managers = $manager;
                $modelDesasignar = CarrierManagers::checkCarrierManager(8, $asignados[$key]);
                $modelDesasignar->end_date = date("Y-m-d");
                          $asigNames.= Carrier::getName($asignados[$key]).",";
            }
        }
        foreach ($noasignados as $key => $value) {
            $model = CarrierManagers::checkCarrierManager($manager, $noasignados[$key]);
            
            if ($model){
                $modelSinAsignar = new CarrierManagers;
                $model->end_date = date("Y-m-d");
                $modelSinAsignar->start_date = date("Y-m-d");
                $modelSinAsignar->id_carrier = $noasignados[$key];
                $modelSinAsignar->id_managers = 8;
//                if ($noasignados==null){
//                     $noasigNames.='';   
//                   }else{
                          $noasigNames.=Carrier::getName($noasignados[$key]).",";   
//                        }
            }
        }
        echo $managerNames.'/'.$noasigNames.'/'.$asigNames;
                }
        }else{
            echo "Debe seleccionar un Manager";
        }
    }

}
