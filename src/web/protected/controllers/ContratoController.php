<?php

class ContratoController extends Controller
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
				'actions'=>array('index','view','DynamicDatosContrato'),
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
		$model=new Contrato;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Contrato']))
		{
                    $sign_date=$_POST['Contrato']['sign_date'];
                    $production_date=$_POST['Contrato']['production_date'];
                    $end_date=$_POST['Contrato']['end_date'];
                    $company=$_POST['Contrato']['id_company'];
                    $carrier=$_POST['Contrato']['id_carrier'];
                    $termino_pago=$_POST['Contrato']['id_termino_pago'];
                    $monetizable=$_POST['Contrato']['id_monetizable'];
                    $dias_disputa=$_POST['Contrato']['id_disputa'];
                    $modelAux=Contrato::model()->find('sign_date=:sign_date AND id_carrier=:carrier and end_date IS NULL',array(':sign_date'=>$sign_date,':carrier'=>$carrier));
			if($modelAux != NULL){
                            /*YA EXISTE*/
                                $modelAux->sign_date=$sign_date;
                                $modelAux->production_date=$production_date;
                                $modelAux->id_company=$company;
                                if ($end_date!='' || $end_date!=NULL){
                                    $modelAux->end_date=$end_date;
                                }else{
                                    $modelAux->end_date=NULL;
                                }
                                /*TERMINO PAGO*/
                                $modelCTP=ContratoTerminoPago::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                                if($modelCTP!=NULL){
                                    if($modelCTP->id_termino_pago != $termino_pago){
                                        $modelCTP->end_date=date('Y-m-d'); 
                                        $modelCTP->save();
                                        $modelCTPNEW = new ContratoTerminoPago;
                                        $modelCTPNEW->id_contrato=$modelAux->id;
                                        $modelCTPNEW->start_date=date('Y-m-d');
                                        $modelCTPNEW->id_termino_pago =$termino_pago;
                                        $modelCTPNEW->save();
                                        Log::registrarLog(33,NULL, $modelCTPNEW->id);
                                    }
                                }else{
                                        $modelCTPNEW = new ContratoTerminoPago;
                                        $modelCTPNEW->id_contrato=$modelAux->id;
                                        $modelCTPNEW->start_date=date('Y-m-d');
                                        $modelCTPNEW->id_termino_pago =$termino_pago;
                                        $modelCTPNEW->save();
                                        Log::registrarLog(33,NULL, $modelCTPNEW->id);
                                }
                                /*MONETIZABLE*/
                                $modelCM=  ContratoMonetizable::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                                if($modelCM!=NULL){
                                    if($modelCM->id_monetizable != $monetizable){
                                        $modelCM->end_date=date('Y-m-d'); 
                                        $modelCM->save();
                                        $modelCMNEW = new ContratoMonetizable;
                                        $modelCMNEW->id_contrato=$modelAux->id;
                                        $modelCMNEW->start_date=date('Y-m-d');
                                        $modelCMNEW->id_monetizable =$monetizable;
                                        $modelCMNEW->save();
                                        Log::registrarLog(34,NULL, $modelCMNEW->id);
                                    }
                                }else{
                                        $modelCMNEW = new ContratoMonetizable;
                                        $modelCMNEW->id_contrato=$modelAux->id;
                                        $modelCMNEW->start_date=date('Y-m-d');
                                        $modelCMNEW->id_monetizable =$monetizable;
                                        $modelCMNEW->save();
                                        Log::registrarLog(34,NULL, $modelCMNEW->id);
                                }
                                /*DIAS_DISPUTA*/
                                $modelCD= DaysDisputeHistory::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                                if($modelCD!=NULL){
                                    if($modelCD->days != $dias_disputa){
                                        $modelCD->end_date=date('Y-m-d'); 
                                        $modelCD->save();
                                        $modelCDNEW = new DaysDisputeHistory;
                                        $modelCDNEW->id_contrato=$modelAux->id;
                                        $modelCDNEW->start_date=date('Y-m-d');
                                        $modelCDNEW->days =$dias_disputa;
                                        $modelCDNEW->save();
                                        Log::registrarLog(38,NULL, $modelCDNEW->id);
                                    }
                                }else{
                                        $modelCDNEW = new DaysDisputeHistory;
                                        $modelCDNEW->id_contrato=$modelAux->id;
                                        $modelCDNEW->start_date=date('Y-m-d');
                                        $modelCDNEW->days =$dias_disputa;
                                        $modelCDNEW->save();
                                        Log::registrarLog(38,NULL, $modelCDNEW->id);
                                }
                                if($modelAux->save())
                                    $this->redirect(array('view','id'=>$modelAux->id));
                        }else{
                            /*NUEVO CONTRATO*/
                                $model->attributes=$_POST['Contrato'];
                                $model->end_date=NULL;
                                $model->save();       
                                /*TERMINO PAGO*/
                                if($termino_pago!='' || $termino_pago!=NULL){
                                $modelCTPNEW = new ContratoTerminoPago;
                                $modelCTPNEW->id_contrato=$model->id;
                                $modelCTPNEW->start_date=date('Y-m-d');
                                $modelCTPNEW->id_termino_pago =$termino_pago;
                                $modelCTPNEW->save();
                                }
                                /*MONETIZABLE*/
                                if($monetizable!='' || $monetizable!=NULL){
                                $modelCMNEW = new ContratoMonetizable;
                                $modelCMNEW->id_contrato=$model->id;
                                $modelCMNEW->start_date=date('Y-m-d');
                                $modelCMNEW->id_monetizable =$monetizable;
                                $modelCMNEW->save();
                                }
                                /*DIAS_DISPUTA*/
                                if($dias_disputa!='' || $dias_disputa!=NULL){
                                $modelCDNEW = new DaysDisputeHistory;
                                $modelCDNEW->id_contrato=$model->id;
                                $modelCDNEW->start_date=date('Y-m-d');
                                $modelCDNEW->days =$dias_disputa;
                                $modelCDNEW->save();
                                }
                                $this->redirect(array('view','id'=>$model->id));
                        }
                     
                
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

		if(isset($_POST['Contrato']))
		{
			$model->attributes=$_POST['Contrato'];
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
		$dataProvider=new CActiveDataProvider('Contrato');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Contrato('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Contrato']))
			$model->attributes=$_GET['Contrato'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Contrato the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Contrato::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Contrato $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='contrato-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
       public function actionDynamicDatosContrato()
        {           
           $model = Contrato::DatosContrato($_GET['idCarrier']);

           if($model!=NULL){      
                $params['company']=$model->id_company;
                $params['sign_date']=$model->sign_date;
                $params['production_date']=$model->production_date;
                $params['termino_pago']=ContratoTerminoPago::getTpId($model->id);
                $params['monetizable']=  ContratoMonetizable::getMonetizableId($model->id);
                $params['manager']= Managers::getName(CarrierManagers::getIdManager($model->id_carrier));
                $params['dias_disputa']=  DaysDisputeHistory::getDays($model->id);
                $params['carrier']= Carrier::getName($model->id_carrier);
                $params['fechaManager']=CarrierManagers::getFechaManager($model->id_carrier);
           }else{
                $params['company']=''; 
                $params['sign_date']='';
                $params['production_date']='';
                $params['termino_pago']='';
                $params['monetizable']='';
                $params['manager']='';
                $params['dias_disputa']='';
                $params['carrier']= Carrier::getName($_GET['idCarrier']);
                $params['fechaManager']=CarrierManagers::getFechaManager($_GET['idCarrier']);
           }
           echo json_encode($params);
        }       

}