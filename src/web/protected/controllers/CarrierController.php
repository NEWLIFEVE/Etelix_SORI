<?php

class CarrierController extends Controller
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
				'actions'=>array('index','view','contrato','newGroupCarrier','saveCarrierGroup','buscaNombres'),
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
                $nombre =  Carrier::model()->findByPk($id)->name;
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
		$model=new Carrier;
		$model->scenario="create";
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Carrier']))
		{
			$model->attributes=$_POST['Carrier'];
			$model->fecha_registro=date("Y-m-d", time());
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

		if(isset($_POST['Carrier']))
		{
			$model->attributes=$_POST['Carrier'];
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
//		$dataProvider=new CActiveDataProvider('Carrier');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
            		$model=new Carrier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Carrier']))
			$model->attributes=$_GET['Carrier'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Carrier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Carrier']))
			$model->attributes=$_GET['Carrier'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Carrier the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Carrier::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Carrier $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='carrier-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
         /**
         *solo rebnderiza a la vista de nuevogrupocarrier
	 */
        public function actionNewGroupCarrier()
	{
		$model=new Carrier;
		
		$this->render('newGroupCarrier',array(
			'model'=>$model,
		));
	}
         /**
	 *  recibe el valor de $grupo $asignados $noasignados
         *  va a ejecutar una de las consultas al modelo
         * guarda la asignacion de carriers a los grupos, y asigna el valor de principal al grupo, si ya esta asignado, solo actualiza y devuelve a views .js para ser vistos en la vista
	 */
        public function actionSaveCarrierGroup()
	{
            $grupo=$_GET['grupo'];//el valor es un id de grupo
            $asignados=explode(',', $_GET['asignados']); // convierto el string a un array.
            $noasignados=explode(',', $_GET['noasignados']); // convierto el string a un array.  
            $asigSave="";
            $noasigSave="";
            foreach ($asignados as $key => $value) {
                $modelAsig = Carrier::model()->findByPk($asignados[$key]); 
                $modelAsig->id_carrier_groups = $grupo;
                if($modelAsig->save()){                  
                    $asigSave.= $modelAsig->name.", ";   
                }               
            }
            foreach ($noasignados as $key => $value) {
                $modelNoAsig = Carrier::model()->findByPk($noasignados[$key]);
                $modelNoAsig->id_carrier_groups = NULL;
                $modelNoAsig->group_leader = NULL;
                if($modelNoAsig->save()){
                $noasigSave.=$modelNoAsig->name.", ";
                }
            }
            $buscaUno=Carrier::getSerchOne($grupo);            //busca si hay algun carrier con el id_carrier_group sea igual a $grupo y carrier_Leader sea igual a'1' 
            
            if($buscaUno==NULL){
                $grupoCarrier = Carrier::getID_G($grupo);      //* con la id de grupo, busca el id carrier donde el id_carrier_groups sea igual a $grupo
                $model=$this->loadModel($grupoCarrier);        //* carga la fila donde el id sea igual a $grupoCarrier para actualizarlo y colocarle en
                                                               //group_leader el valor 1, solo lo agregara al primero que consiga...
                $model->group_leader = '1';
                $model->save();
            }
            $idCarrierName= CarrierGroups::getName($grupo);//* esto solo es para traer el nombre del grupo, que sera mostrado en el msj

            $params['grupo']=$idCarrierName;    
            $params['asignados']=$asigSave;    
            $params['noasignados']=$noasigSave;    
               echo json_encode($params);
	}
         /**
	 *  recibe el valor de $grupo $asignados $noasignados
         *  va a ejecutar una de las consultas al modelo
         * trae los nombres pertenecientes a views .js para ser vistos en la vista
	 */
         public function actionBuscaNombres()
	{
            $grupo=$_GET['grupo'];
            $asignados=explode(',', $_GET['asignados']); // convierto el string a un array.
            $noasignados=explode(',', $_GET['noasignados']); // convierto el string a un array.  

            $asigNames="";
            $noasigNames="";
            $grupoName="";
            $grupoName.= CarrierGroups::getName($grupo);
            foreach ($asignados as $key => $value) {
                $modelAsig = Carrier::model()->findByPk($asignados[$key]); 
                if ($modelAsig->id_carrier_groups != $grupo)
                    $asigNames.= $modelAsig->name.", ";      
            }
            foreach ($noasignados as $key => $value) {
                $modelNoAsig = Carrier::model()->findByPk($noasignados[$key]);
                if ($modelNoAsig->id_carrier_groups != NULL)  
                $noasigNames.=$modelNoAsig->name.", ";
            }

                    $params['grupo']=$grupoName;    
                    $params['asignados']=$asigNames;    
                    $params['noasignados']=$noasigNames;    
                       echo json_encode($params);
	}
        
}
