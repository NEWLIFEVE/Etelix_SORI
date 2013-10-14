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
				'actions'=>array('index','view','DynamicDatosContrato','Contrato','ContratoConfirma'),
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
		 //$this->performAjaxValidation($model);

//		if(isset($_POST['Contrato']))
//		{
//                     
//                
//		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
       public function actionContratoConfirma()
       {    
                    $company=$_GET['id_company'];
                    $carrier=$_GET['id_carrier'];
                    $termino_pago=$_GET['id_termino_pago'];
                    $monetizable=$_GET['id_monetizable'];
                    $termino_pagoO=$_GET['id_TP_Oculto'];
                    $monetizableO=$_GET['id_M_Oculto'];
                    $companyName='';
                    $carrierName='';
                    $monetizableName='';
                    $termino_pName='';
                    $termino_pNameO='';
                    $monetizableNameO='';
                    $companyName.=Company::getName($company);
                    $carrierName.=Carrier::getName($carrier); 
                    $termino_pName.= TerminoPago::getName($termino_pago);
                    $monetizableName.= Monetizable::getName($monetizable);
                    if ($termino_pagoO=='' || $monetizableO=='')
                    {
                       $termino_pNameO.=false; 
                       $monetizableNameO.=false;
                    }
                    else
                        {
                        $termino_pNameO.= TerminoPago::getName($termino_pagoO);
                        $monetizableNameO.= Monetizable::getName($monetizableO);
                        }
                    $params['carrierName']=$carrierName;    
                    $params['companyName']=$companyName;    
                    $params['termino_pName']=$termino_pName;    
                    $params['monetizableName']=$monetizableName;    
                    $params['monetizableNameO']=$monetizableNameO;    
                    $params['termino_pNameO']=$termino_pNameO;    
                    echo json_encode($params);
    }
        
	public function actionContrato()
	{
		$model=new Contrato;

		// Uncomment the following line if AJAX validation is needed
		 //$this->performAjaxValidation($model);
               
//		if(isset($_POST['Contrato']))
//		{
                    $sign_date=$_GET['sign_date'];
                    $production_date=$_GET['production_date'];
                    $end_date=$_GET['end_date'];
                    $company=$_GET['id_company'];
                    $carrier=$_GET['id_carrier'];
                    $termino_pago=$_GET['id_termino_pago'];
                    $monetizable=$_GET['id_monetizable'];
                    $dias_disputa=$_GET['dias_disputa'];
                    $credito=$_GET['credito'];
                    $compra=$_GET['compra'];
                    $termino_pName='';
                    $monetizaName='';
                    $companyName='';
                    $carrierName='';
                    $text='';
                    $companyName.=Company::getName($company);
                    $carrierName.=Carrier::getName($carrier);
                    $sign_date.=Utility::snull($sign_date);
                    $production_date.=Utility::snull($production_date);


                    $modelAux=Contrato::model()->find('id_carrier=:carrier',array(':carrier'=>$carrier));
                    /*
                     * ESTE CAMBIO ES PROVISIONAL, MIENTRAS NO SE POSEA LA INFORMACION DE LAS FECHAS
                     *SE HIZO ESTE CAMBIO PARA EVITAR QUE EL CODIGO GENERE NUEVOS CONTRATOS A LA HORA DE MODIFICAR,
                     * POR OTRO LADO, ASI EL CODIGO NO PERMITE CREAR CONTRATOS NUEVOS PARA CARRIER YA EXISTENTES EN LA TABLA CONTRATO
                     */
//                    $modelAux=Contrato::model()->find('sign_date=:sign_date AND id_carrier=:carrier and end_date IS NULL',array(':sign_date'=>$sign_date,':carrier'=>$carrier));

			if($modelAux != NULL){
                            /*YA EXISTE*/
                                $modelAux->sign_date=$sign_date;
                                $modelAux->production_date=$production_date;
                                $modelAux->id_company=$company;
                                $modelAux->end_date = Utility::snull($end_date);

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
                                        Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTPNEW->id);

                                        $text.= $termino_pago.',';
                                        $termino_pName.= TerminoPago::getName($termino_pago);
                                    }
                                }else{
                                        $modelCTPNEW = new ContratoTerminoPago;
                                        $modelCTPNEW->id_contrato=$modelAux->id;
                                        $modelCTPNEW->start_date=date('Y-m-d');
                                        $modelCTPNEW->id_termino_pago =$termino_pago;
                                        $modelCTPNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTPNEW->id);
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
                                        Log::registrarLog(LogAction::getId('Modificar Monetizable'),NULL, $modelCMNEW->id);
                                        $text.= $monetizable.',';
                                        $monetizaName.= Monetizable::getName($monetizable);
                                    }
                                }else{
                                        $modelCMNEW = new ContratoMonetizable;
                                        $modelCMNEW->id_contrato=$modelAux->id;
                                        $modelCMNEW->start_date=date('Y-m-d');
                                        $modelCMNEW->id_monetizable =$monetizable;
                                        $modelCMNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Monetizable'),NULL, $modelCMNEW->id);
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
                                        Log::registrarLog(LogAction::getId('Modificar Dias Max Disputa'),NULL, $modelCDNEW->id);
                                        $text.= $dias_disputa.',';
                                    }
                                }else{
                                        $modelCDNEW = new DaysDisputeHistory;
                                        $modelCDNEW->id_contrato=$modelAux->id;
                                        $modelCDNEW->start_date=date('Y-m-d');
                                        $modelCDNEW->days =$dias_disputa;
                                        $modelCDNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Dias Max Disputa'),NULL, $modelCDNEW->id);
                                }
                                /*CREDITO*/
                                $modelCLCredito= CreditLimit::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                                if($modelCLCredito!=NULL){
                                    if($modelCLCredito->amount != $credito){
                                        $modelCLCredito->end_date=date('Y-m-d'); 
                                        $modelCLCredito->save();
                                        $modelCLCreditoNEW = new CreditLimit;
                                        $modelCLCreditoNEW->id_contrato=$modelAux->id;
                                        $modelCLCreditoNEW->start_date=date('Y-m-d');
                                        $modelCLCreditoNEW->amount =$credito;
                                        $modelCLCreditoNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Limite de Credito'),NULL, $modelCLCreditoNEW->id);
                                        $text.= $credito.',';
                                        
                                    }
                                }else{
                                        $modelCLCreditoNEW = new CreditLimit;
                                        $modelCLCreditoNEW->id_contrato=$modelAux->id;
                                        $modelCLCreditoNEW->start_date=date('Y-m-d');
                                        $modelCLCreditoNEW->amount =$credito;
                                        $modelCLCreditoNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Limite de Credito'),NULL, $modelCLCreditoNEW->id);
                                }                        
                                /*COMPRA*/
                                $modelCLCompra= PurchaseLimit::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                                if($modelCLCompra!=NULL){
                                    if($modelCLCompra->amount != $compra){
                                        $modelCLCompra->end_date=date('Y-m-d'); 
                                        $modelCLCompra->save();
                                        $modelCLCompraNEW = new PurchaseLimit;
                                        $modelCLCompraNEW->id_contrato=$modelAux->id;
                                        $modelCLCompraNEW->start_date=date('Y-m-d');
                                        $modelCLCompraNEW->amount =$compra;
                                        $modelCLCompraNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Limite de Compra'),NULL, $modelCLCompraNEW->id);
                                        $text.= $compra.',';
                                        
                                    }
                                }else{
                                        $modelCLCompraNEW = new PurchaseLimit;
                                        $modelCLCompraNEW->id_contrato=$modelAux->id;
                                        $modelCLCompraNEW->start_date=date('Y-m-d');
                                        $modelCLCompraNEW->amount =$compra;
                                        $modelCLCompraNEW->save();
                                        Log::registrarLog(LogAction::getId('Modificar Limite de Compra'),NULL, $modelCLCompraNEW->id);
                                }
                                $modelAux->save();      
                        }else{
                            /*NUEVO CONTRATO*/
                                $model->id_company=$company;
                                $model->id_carrier=$carrier;
                                $model->sign_date=$sign_date;
                                $model->production_date=$production_date;
                                $model->end_date=NULL;
                                $model->save();  
                                Log::registrarLog(LogAction::getId('Crear Contrato'),NULL, $model->id);
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
                                /*CREDITO*/
                                if($credito!='' || $credito!=NULL){
                                $modelCLCreditoNEW = new CreditLimit;
                                $modelCLCreditoNEW->id_contrato=$model->id;
                                $modelCLCreditoNEW->start_date=date('Y-m-d');
                                $modelCLCreditoNEW->amount =$credito;
                                $modelCLCreditoNEW->save();
                                }                                
                                           
                                /*COMPRA*/
                                if($compra!='' || $compra!=NULL){
                                $modelCLCompraNEW = new PurchaseLimit;
                                $modelCLCompraNEW->id_contrato=$model->id;
                                $modelCLCompraNEW->start_date=date('Y-m-d');
                                $modelCLCompraNEW->amount =$compra;
                                $modelCLCompraNEW->save();
                                }                                
                        }                   
//		}
             echo $carrierName.'|'.$companyName.'|'.$termino_pName.'|'.$monetizaName.'|'.$dias_disputa.'|'.$credito.'|'.$compra;

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
                
                $params['managerUP']= Managers::getUP(CarrierManagers::getIdManager($model->id_carrier));
                
                $params['dias_disputa']= DaysDisputeHistory::getDays($model->id);
                $params['carrier']= Carrier::getName($model->id_carrier);
//                $params['credito']= ContratoLimites::getCredito($model->id);
//                $params['compra']= ContratoLimites::getCompra($model->id);
                $params['credito']= CreditLimit::getCredito($model->id);
                $params['compra']= PurchaseLimit::getCompra($model->id);
                $params['fechaManager']=CarrierManagers::getFechaManager($model->id_carrier);
                
           }else{
                $params['company']=''; 
                $params['sign_date']='';
                $params['production_date']='';
                $params['termino_pago']='';
                $params['monetizable']='';
                $params['manager']=Managers::getName(CarrierManagers::getIdManager($_GET['idCarrier']));;
                $params['managerUP']=Managers::getUP(CarrierManagers::getIdManager($_GET['idCarrier']));;
                $params['dias_disputa']='';
                $params['credito']='';
                $params['compra']='';
                $params['carrier']= Carrier::getName($_GET['idCarrier']);
                $params['fechaManager']=CarrierManagers::getFechaManager($_GET['idCarrier']);
           }
           echo json_encode($params);
        }       

}
