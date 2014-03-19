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

				'actions'=>array('index','view','EnviarEmail','GuardarDoc_ContTemp','GuardarListaFinal','delete', 'borrar','update','GuardarFac_RecTemp','GuardarFac_EnvTemp','GuardarPagoTemp','GuardarCobroTemp','BuscaFactura','GuardarDisp_RecTemp','GuardarNotaC_Env','GuardarDisp_EnvTemp','DestinosSuppAsignados','print','BuscaDisputaRec','BuscaDisputaEnv','GuardarNotaC_Rec','UpdateDisp'),
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
		$lista_FacEnv=AccountingDocumentTemp::listaFacturasEnviadas(Yii::app()->user->id);
		$lista_FacRec=AccountingDocumentTemp::listaFacRecibidas(Yii::app()->user->id);
		$lista_Pagos=AccountingDocumentTemp::listaPagos(Yii::app()->user->id);
		$lista_Cobros=AccountingDocumentTemp::listaCobros(Yii::app()->user->id);
		$lista_DispRec=AccountingDocumentTemp::lista_DispRec(Yii::app()->user->id);
		$lista_DispEnv=AccountingDocumentTemp::lista_DispEnv(Yii::app()->user->id);
		$lista_NotCredEnv=AccountingDocumentTemp::lista_NotCredEnv(Yii::app()->user->id);
		$lista_NotCredRec=AccountingDocumentTemp::lista_NotCredRec(Yii::app()->user->id);
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccountingDocumentTemp']))
		{
			$model->attributes=$_POST['AccountingDocumentTemp'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,'lista_FacEnv'=>$lista_FacEnv,
                                        'lista_FacRec'=>$lista_FacRec,
                                        'lista_Pagos'=>$lista_Pagos,
                                        'lista_Cobros'=>$lista_Cobros,
                                        'lista_DispRec'=>$lista_DispRec,
                                        'lista_DispEnv'=>$lista_DispEnv,
                                        'lista_NotCredEnv'=>$lista_NotCredEnv,
                                        'lista_NotCredRec'=>$lista_NotCredRec
		));
	}
        
        /**
        * recibe los datos desde ajax y almacena solo las facturas enviadas doc temp...
        * @access public
        **/
        public function actionGuardarDoc_ContTemp() 
        {
            if(!Yii::app()->user->isGuest)
            {
                $model = new AccountingDocumentTemp;
                $model->attributes = $_GET['AccountingDocumentTemp'];
                $model = $model->setValues($model);    
                $ValidADT = AccountingDocumentTemp::getExist($model);
                $ValidAD = AccountingDocument::getExist($model);
                if($ValidAD==NULL && $ValidADT==NULL){
                if ($model->save()) {
                    $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                    Log::registrarLog($idAction, NULL, $model->id);
                    if($model->bank_fee!=NULL){  $model->saveBankFee($model);}
                      else{$model->bank_fee="N/A";}
                    echo json_encode(AccountingDocumentTemp::getJSonParams($model,1));
                    }
                }else{
                    echo json_encode(AccountingDocumentTemp::getJSonParams($model,0));
                }
            }else{
                $params['valid']=2;
                echo json_encode($params);
            }
        }
        /**
         * se encarga de guardar los documentos temporales en la tabla de documentos contables definitiva
         * ademas guarda en log, y elimina los documentos de la tabla temporal
         * solo copia los mismos parametros... con setatributes $modelADT
         */

        public function actionGuardarListaFinal() 
        {
            $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
            $idUsers = Yii::app()->user->id;
            $modelLog = Log::model()->findAll('id_log_action=:idAction AND id_users=:idUsers', array(":idAction" => $idAction, ":idUsers" => $idUsers));
            if ($modelLog != null) {
                $count = 0;
                foreach ($modelLog as $key => $Log) {
                    $modelADT = AccountingDocumentTemp::model()->findByPk($Log->id_esp);
                    if ($modelADT != null && $modelADT->id_type_accounting_document != "14") {
                        $modelAD = new AccountingDocument;
                        $modelAD->setAttributes($modelADT->getAttributes());
                        if ($modelAD->save()) {
                            AccountingDocument::UpdateProv($modelAD);
                            $this->saveBankFeeFinal($modelADT,$modelAD);                            
                            $modelADT->deleteByPk($Log->id_esp);
                            $idAction = LogAction::getLikeId('Crear Documento Contable Final');
                            Log::registrarLog($idAction, NULL, $modelAD->id);
                            Log::updateDocLog($Log, $modelAD->id);
                            $count++;
                        }
                    }
                }
                echo $count;
            }
        }
        /**
         * 
         * @param type $modelADT
         * @param type $modelAD
         */
        public function saveBankFeeFinal($modelADT,$modelAD)
        {
             if($modelADT->id_type_accounting_document=="3" && $modelAD->id_type_accounting_document=="3"||$modelAD->id_type_accounting_document=="4"&& $modelADT->id_type_accounting_document=="4"){
               $modelBFT= AccountingDocumentTemp::getid_bank_fee($modelADT->id);
                 if($modelBFT != NULL){
                     $modelBF = new AccountingDocument;
                     $modelBF->setAttributes($modelBFT->getAttributes());
                     $modelBF->id_accounting_document=$modelAD->id;
                     if($modelBF->save()){
                         $this->loadModel($modelBFT->id)->delete();
                     } 
                 }
             }
        }
	/**
         * Updates a particular model(modificado).
         * If update is successful, the browser will be redirected to the 'view' page.
         * @access public
         * @param integer $id the ID of the model to be updated
         */
        public function actionUpdate($id)
        {
            $type_doc=AccountingDocumentTemp::getTypeDoc($id);    
                
            $model=$this->loadModel($id);     
            if(isset($_POST['AccountingDocumentTemp']))
            {
                if(isset($_POST['AccountingDocumentTemp']['issue_date']))$model->issue_date=Utility::snull($_POST['AccountingDocumentTemp']['issue_date']); 
                if(isset($_POST['AccountingDocumentTemp']['from_date']))$model->from_date=Utility::snull($_POST['AccountingDocumentTemp']['from_date']);
                if(isset($_POST['AccountingDocumentTemp']['to_date']))$model->to_date=Utility::snull($_POST['AccountingDocumentTemp']['to_date']);
                if(isset($_POST['AccountingDocumentTemp']['sent_date']))$model->sent_date=Utility::snull($_POST['AccountingDocumentTemp']['sent_date']); 
                if(isset($_POST['AccountingDocumentTemp']['email_received_date']))$model->email_received_date=Utility::snull($_POST['AccountingDocumentTemp']['email_received_date']); 
                if(isset($_POST['AccountingDocumentTemp']['valid_received_date']))$model->valid_received_date=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_date']); 
                if(isset($_POST['AccountingDocumentTemp']['email_received_hour']))$model->email_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['email_received_hour']); 
                if(isset($_POST['AccountingDocumentTemp']['valid_received_hour']))$model->valid_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_hour']); 
                if(isset($_POST['AccountingDocumentTemp']['doc_number']))$model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']); 
                if(isset($_POST['AccountingDocumentTemp']['minutes']))$model->minutes=Utility::snull($_POST['AccountingDocumentTemp']['minutes']); 
//                if(isset($_POST['AccountingDocumentTemp']['amount']))$model->amount=Utility::snull(Utility::ComaPorPunto(Utility::changePositive($_POST['AccountingDocumentTemp']['amount']))); 
                if(isset($_POST['AccountingDocumentTemp']['amount']))$model->amount=Utility::snull(Utility::ComaPorPunto($_POST['AccountingDocumentTemp']['amount'])); 
                if(isset($_POST['AccountingDocumentTemp']['min_carrier']))$model->min_carrier=Utility::snull($_POST['AccountingDocumentTemp']['min_carrier']); 
                if(isset($_POST['AccountingDocumentTemp']['rate_carrier']))$model->rate_carrier=Utility::snull($_POST['AccountingDocumentTemp']['rate_carrier']);
                if(isset($_POST['AccountingDocumentTemp']['min_etx']))$model->min_etx=Utility::snull($_POST['AccountingDocumentTemp']['min_etx']);
                if(isset($_POST['AccountingDocumentTemp']['rate_etx']))$model->rate_etx=Utility::snull($_POST['AccountingDocumentTemp']['rate_etx']);
                if(isset($_POST['AccountingDocumentTemp']['id_currency']))$model->id_currency=Currency::getID($_POST['AccountingDocumentTemp']['id_currency']); 

                if($model->save()){
                            echo "Actualizado id: ".$model->id;
                }else{
                            echo "Algo salio mal";
                }
            }
            if($type_doc->id_type_accounting_document==3||$type_doc->id_type_accounting_document==4){                                                
                $this->updateBackFee($id);
            }
        }
        /**
         * modifica el banck fee apartir del monto banck fee en el cobro
         * @param type $id
         */
        public function updateBackFee($id)
        {
           $bank_fee=AccountingDocumentTemp::getid_bank_fee($id); 
                if($bank_fee->id!=NULL){
                    $model=$this->loadModel($bank_fee->id);     
                    if(isset($_POST['AccountingDocumentTemp']['amount_bank_fee']))$model->amount=Utility::snull(Utility::ComaPorPunto($_POST['AccountingDocumentTemp']['amount_bank_fee'])); 
                    if($model->save()){
                                echo " y id: ".$model->id;
                    }else{
                                echo "Algo salio mal";
                    } 
                }
        }
        /**
         * modifica el monto disputa en disputas recibidas y enviadas
         * @param type $id
         */
        public function ActionUpdateDisp($id)
        {
            $model=$this->loadModel($id); 
             $model->amount=Utility::snull(Utility::ComaPorPunto($_POST['dispute'])); 
//             $model->amount=Utility::snull(Utility::ComaPorPunto(Utility::changePositive($_POST['dispute']))); 
            if($model->save()){
                   echo " y id: ".$model->id;
              }else{
                   echo "Algo salio mal";
              } 
             
        }
        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'admin' page.
         * CASO PARTICULAR DE COBROS Y BANK FEE-> "busca el id_type_accounting_document del documento, antes de eliminarlo
         * chequea si id_type_accounting_document es igual a cobro, se ser asi utiliza el $id para buscar id del bank fee
         * y de esta manera usa el id del documento bank fee para eliminarlo"
         * @access public
         * @param integer $id the ID of the model to be deleted
         */
	public function actionBorrar($id)
	{
            $type_doc=AccountingDocumentTemp::getTypeDoc($id); 
            if($type_doc->id_type_accounting_document==3||$type_doc->id_type_accounting_document==4){                                                 
                $id_bank_fee=AccountingDocumentTemp::getid_bank_fee($id); 
                if($id_bank_fee->id!=NULL)$this->loadModel($id_bank_fee->id)->delete();
            }
            $this->loadModel($id)->delete();
            var_dump("elimino el documento: ".$id." y bank fee: ".$id_bank_fee->id);
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

        /**
         * esta funcion busca la factura para disputas, 
         * se le pasa el carrier, inicio de periodo a facturar y el fin de periodo a facturar
         */
        public function actionBuscaFactura() 
        {   
            $id=AccountingDocument::getId_deDoc($_GET['CarrierDisp'],$_GET['desdeDisp'],$_GET['hastaDisp'],$_GET['tipoDoc']);
            $factura=AccountingDocument::getDocNumCont($_GET['CarrierDisp'],$_GET['desdeDisp'],$_GET['hastaDisp'],$_GET['tipoDoc']);
            $llave=0;
            $params = array();
        foreach($factura as $id=>$factura)
           {
                $params[$llave]['id'] =$id; 
                $params[$llave]['factura'] =$factura; 
           $llave++;   
           }    
        echo json_encode($params);   
        }
        
        /**
         * esta funcion busca las disputas, 
         * se le pasa el carrier, inicio de periodo a facturar y el fin de periodo a facturar
         */
        public function actionBuscaDisputaRec() 
        {
            $model = new AccountingDocumentTemp;
            $model->attributes=$_GET['AccountingDocumentTemp'];
            $params = array();
            $lista_Disp_NotaCEnv = AccountingDocument::lista_Disp_NotaCRec($model->id_accounting_document);
            foreach ($lista_Disp_NotaCEnv as $key => $disputa) {
                   $params[$key]['id']=$disputa->id;
                   $params[$key]['id_destination']=$disputa->id_destination;
                   $params[$key]['min_etx']=$disputa->min_etx;
                   $params[$key]['min_carrier']=$disputa->min_carrier;
                   $params[$key]['rate_etx']=$disputa->rate_etx;
                   $params[$key]['rate_carrier']=$disputa->rate_carrier;
                   $params[$key]['amount_etx']=$disputa->amount_etx;
                   $params[$key]['amount_carrier']=$disputa->amount_carrier;
                   $params[$key]['dispute']=$disputa->dispute;
                   
               }echo json_encode($params);  
           }
        
        /**
         * esta funcion busca las disputas enviadas para notas de credito recibidas, 
         * se le pasa el carrier, inicio de periodo a facturar y el fin de periodo a facturar
         */
        public function actionBuscaDisputaEnv() 
        {
            $model = new AccountingDocument;
            $model->attributes=$_GET['AccountingDocumentTemp'];
            $params = array();
            $lista_Disp_NotaCRec = AccountingDocument::lista_Disp_NotaCEnv($model->id_accounting_document);
            foreach ($lista_Disp_NotaCRec as $key => $disputa) {
                   $params[$key]['id']=$disputa->id;
                   $params[$key]['id_destination']=$disputa->id_destination_supplier;
                   $params[$key]['min_etx']=$disputa->min_etx;
                   $params[$key]['min_carrier']=$disputa->min_carrier;
                   $params[$key]['rate_etx']=$disputa->rate_etx;
                   $params[$key]['rate_carrier']=$disputa->rate_carrier;
                   $params[$key]['amount_etx']=$disputa->amount_etx;
                   $params[$key]['amount_carrier']=$disputa->amount_carrier;
                   $params[$key]['dispute']=$disputa->dispute;
                   
               }echo json_encode($params);  
           }

        /**
         * busca la lista de destinos supplier asignados al carier, desde documentos temporales, esto con el ajax de yii
         */   
        public function actionDestinosSuppAsignados()
       { 
           $data = AccountingDocumentTemp::getListCarriersAsignados_DestSup($_POST['AccountingDocumentTemp']['id_carrier']);
           foreach($data as $value=>$name)
           {
               echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
           }
       }
        
    /**
     * Action para imprimir los documentos conrtables
     */
    public function actionPrint()
    {
        $lista_FacEnv=AccountingDocumentTemp::listaFacturasEnviadas(Yii::app()->user->id);
        $lista_FacRec=AccountingDocumentTemp::listaFacRecibidas(Yii::app()->user->id);
        $lista_Pagos=AccountingDocumentTemp::listaPagos(Yii::app()->user->id);
        $lista_Cobros=AccountingDocumentTemp::listaCobros(Yii::app()->user->id);
        $lista_DispRec=AccountingDocumentTemp::lista_DispRec(Yii::app()->user->id);
        $lista_DispEnv=AccountingDocumentTemp::lista_DispEnv(Yii::app()->user->id);
        $lista_NotCredEnv=AccountingDocumentTemp::lista_NotCredEnv(Yii::app()->user->id);
        $lista_NotCredRec=AccountingDocumentTemp::lista_NotCredRec(Yii::app()->user->id);

        $this->render('print',array(
            'lista_FacEnv'=>$lista_FacEnv,
            'lista_FacRec'=>$lista_FacRec,
            'lista_Pagos'=>$lista_Pagos,
            'lista_Cobros'=>$lista_Cobros,
            'lista_DispRec'=>$lista_DispRec,
            'lista_DispEnv'=>$lista_DispEnv,
            'lista_NotCredEnv'=>$lista_NotCredEnv,
            'lista_NotCredRec'=>$lista_NotCredRec
        ));
    }
    /**
     * Action para enviar por correo los documentos contables
     */
    public function actionEnviarEmail() 
    {
        $userMail=Users::traeCorreo(Yii::app()->user->id);
//        $userMail="eduardo@newlifeve.com";
//        $userMail="mark182182@gmail.com";
        $value = Yii::app()->enviarEmail->enviar($_POST['html'],$userMail,$_POST['asunto']);
        $this->redirect($_POST['vista']);
        echo $value;
    }
}
