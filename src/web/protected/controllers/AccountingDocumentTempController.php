<?php

class AccountingDocumentTempController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @access public
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
	 * @access public 
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','GuardarListaTemp','GuardarListaFinal','delete', 'borrar','update'),
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
	 * @access public
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
	 * @access public
	 */
	public function actionCreate()
	{
		$model=new AccountingDocumentTemp;
		$lista=AccountingDocumentTemp::listaGuardados(Yii::app()->user->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccountingDocumentTemp']))
		{
			$model->attributes=$_POST['AccountingDocumentTemp'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,'lista'=>$lista
		));
	}

	/**
	 * @access public
	 */
	public function actionGuardarListaTemp() {
            
            $selecTipoDoc = $_GET['selecTipoDoc'];
            $idCarrier = $_GET['idCarrier'];
            $idGrupo = $_GET['idGrupo'];
            $fechaEmision = $_GET['fechaEmision'];
            $desdeFecha = $_GET['desdeFecha'];
            $hastaFecha = $_GET['hastaFecha'];
            $EmailfechaRecepcion = $_GET['EmailfechaRecepcion'];
            $EmailHoraRecepcion = $_GET['EmailHoraRecepcion'];
            $numDocumento = $_GET['numDocumento'];
            $minutos = $_GET['minutos'];
            $cantidad = $_GET['cantidad'];
            $currency = $_GET['currency'];
            $nota = $_GET['nota'];
            $idCarrierName = "";
            $selecTipoDocName = "";
            $valid_received_hour = "";
            $valid_received_date = "";
            $minutosTemp = "";
            $moneda= "";
            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
            $minutosTemp.= Utility::snull($minutos);
//            $moneda.= Currency::getName($currency);

            $model = new AccountingDocumentTemp;
            $model->id_type_accounting_document = $selecTipoDoc;
            $model->issue_date = Utility::snull($fechaEmision);
            $model->from_date = Utility::snull($desdeFecha);
            $model->to_date = Utility::snull($hastaFecha);
            $model->sent_date = Utility::snull($fechaEmision);
            $model->doc_number = $numDocumento;
            $model->minutes = $minutosTemp;
            $model->amount = Utility::snull($cantidad);
            $model->note = Utility::snull($nota);
            
                if ($idCarrier==''||$idCarrier==NULL){
                    $idCarrierName.= CarrierGroups::getName($idGrupo);
                    $grupoCarrier = Carrier::getCarrierLeader($idGrupo);
                    $model->id_carrier = $grupoCarrier;
                }else{
                $model->id_carrier = $idCarrier;
                $idCarrierName.= Carrier::getName($idCarrier);
                }
                
                if ($currency==''||$currency==NULL){
                    $model->id_currency='1';
                    $moneda.=Currency::getName(1);
                }else{
                    $model->id_currency =$currency;
                    $moneda.= Currency::getName($currency);
                }
            if ($selecTipoDoc == '4') {
                $model->email_received_hour = NULL;
                $model->valid_received_hour = NULL;
                $model->email_received_date = NULL;
                $valid_received_date = $EmailfechaRecepcion;
                $EmailfechaRecepcion = '';
                $model->valid_received_date = $valid_received_date;
            } 

            if ($selecTipoDoc == '2') {
            $fecha = strtotime($EmailfechaRecepcion);
            $dia = date("N", $fecha);
                if ($dia == 1 || $dia == 2) {

                    if ($EmailHoraRecepcion >= '08:00' && $EmailHoraRecepcion <= '17:00') {

                        $valid_received_date = $EmailfechaRecepcion;
                        $valid_received_hour = $EmailHoraRecepcion;
                        $model->valid_received_date = $valid_received_date;
                        $model->valid_received_hour = $valid_received_hour;
                        $model->email_received_date = $EmailfechaRecepcion;
                        $model->email_received_hour = $EmailHoraRecepcion;
                      
                    } else {
                        if($EmailHoraRecepcion < '08:00'){
                            $valid_received_date = $EmailfechaRecepcion;
                            $model->valid_received_date = $EmailfechaRecepcion;
                        }else{
                            $valid_received_date = $model->getValidDate($EmailfechaRecepcion, $dia);
                            $model->valid_received_date = $valid_received_date;
                        }
                        $valid_received_hour = '08:00';
                        $model->valid_received_hour = $valid_received_hour;
                        $model->email_received_date = $EmailfechaRecepcion;
                        $model->email_received_hour = $EmailHoraRecepcion;
                    }
                } else {

                    $valid_received_date = $model->getValidDate($EmailfechaRecepcion, $dia);
                    $valid_received_hour = '08:00';
                    $model->valid_received_date = $valid_received_date;
                    $model->valid_received_hour = $valid_received_hour;
                    $model->email_received_date = $EmailfechaRecepcion;
                    $model->email_received_hour = $EmailHoraRecepcion;
                }
            }         

            if ($selecTipoDoc == '1'){
                $model->confirm = 0;
            }else{
                $model->confirm = 1;
            }

            if ($model->save()) {
                $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                Log::registrarLog($idAction, NULL, $model->id);
                
                $params['idCarrierNameTemp'] = $idCarrierName;
                $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                $params['fechaEmisionTemp'] = $fechaEmision;
                $params['desdeFechaTemp'] =  $desdeFecha;
                $params['hastaFechaTemp'] =  $hastaFecha;
                $params['EmailfechaRecepcionTemp'] = $EmailfechaRecepcion;
                $params['EmailHoraRecepcionTemp'] = $EmailHoraRecepcion;
                $params['valid_received_dateTemp'] = $valid_received_date;
                $params['valid_received_hourTemp'] = $valid_received_hour;
                $params['fechaEnvioTemp'] = $fechaEmision;
                $params['numDocumentoTemp'] = $model->doc_number;
                $params['minutosTemp'] = $minutosTemp;
                $params['cantidadTemp'] = $model->amount;
                $params['currencyTemp'] = $moneda;
                
                echo json_encode($params);
            }
    }
        
        public function actionGuardarListaFinal()
        {
            $params = array();
            $idAction=LogAction::getLikeId('Crear Documento Contable Temp');
            $idUsers=Yii::app()->user->id;
            $modelLog= Log::model()->findAll('id_log_action=:idAction AND id_users=:idUsers', array(":idAction"=>$idAction,":idUsers"=>$idUsers));
            if($modelLog!=null)
            {
                $llave=0;
                foreach($modelLog as $key => $Log)
                {
                    $modelADT=AccountingDocumentTemp::model()->findByPk($Log->id_esp);
                    if($modelADT!=null)
                    {
                        $modelAD=new AccountingDocument;
                        $modelAD->setAttributes($modelADT->getAttributes());
                        if($modelAD->save())
                        {
                            $modelADT->deleteByPk($Log->id_esp);
                            $idAction=LogAction::getLikeId('Crear Documento Contable Final');
                            Log::registrarLog($idAction, NULL,$modelAD->id);
                                                        
                            $params[$llave]['tipo']=TypeAccountingDocument::getName($modelAD->id_type_accounting_document);
                            $params[$llave]['carrier']=Carrier::getName($modelAD->id_carrier);
                            $params[$llave]['fecha']=$modelAD->issue_date;
                            $params[$llave]['monto']=$modelAD->amount;
                            $llave++;
                        }
                    }
                }
                echo json_encode($params);         
            }
        }

	/**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @access public
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
                $model=$this->loadModel($id);

                // Uncomment the following line if AJAX validation is needed
                // $this->performAjaxValidation($model);
                /*if(isset($_POST['AccountingDocumentTemp']))
                {
                        $model->attributes=$_POST['AccountingDocumentTemp'];
                        $model->id_type_accounting_document=TypeAccountingDocument::getId($model->id_type_accounting_document);
                        $model->id_carrier=Carrier::getId($model->id_carrier);
                        if($model->save())
                                return "Actualizado id: ".$model->id;
                        else
                                return "Algo salio mal";
                }*/
                if(isset($_POST['AccountingDocumentTemp']))
                {
                    
                        $model->attributes=$_POST['AccountingDocumentTemp'];
//                        $model->id_type_accounting_document=TypeAccountingDocument::getId($_POST['AccountingDocumentTemp']['id_type_accounting_document']);
//                        $model->id_carrier=Carrier::getId($_POST['AccountingDocumentTemp']['id_type_accounting_document']);
                              
                $model->issue_date=Utility::snull($_POST['AccountingDocumentTemp']['issue_date']);
                $model->from_date=Utility::snull($_POST['AccountingDocumentTemp']['from_date']);
                $model->to_date=Utility::snull($_POST['AccountingDocumentTemp']['to_date']);
                $model->email_received_date=Utility::snull($_POST['AccountingDocumentTemp']['email_received_date']);
                $model->valid_received_date=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_date']);
                $model->email_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['email_received_hour']);
                $model->valid_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_hour']);
                $model->sent_date=Utility::snull($_POST['AccountingDocumentTemp']['sent_date']);
                $model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']);
                $model->minutes=Utility::snull($_POST['AccountingDocumentTemp']['minutes']);
                $model->amount=Utility::snull($_POST['AccountingDocumentTemp']['amount']);
                 $id_currency=Currency::getID($_POST['AccountingDocumentTemp']['id_currency']);
-               $model->id_currency=$id_currency;
                        if($model->save())
                                return "Actualizado id: ".$model->id;
                        else
                                return "Algo salio mal";
                }
                /*$this->render('update',array(
                        'model'=>$model,
                ));*/
        }

        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'admin' page.
         * @access public
         * @param integer $id the ID of the model to be deleted
         */
	/*public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}*/
	public function actionBorrar($id)
	{
		$this->loadModel($id)->delete();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('AccountingDocumentTemp');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AccountingDocumentTemp('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AccountingDocumentTemp']))
			$model->attributes=$_GET['AccountingDocumentTemp'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AccountingDocumentTemp the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AccountingDocumentTemp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AccountingDocumentTemp $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='accounting-document-temp-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
