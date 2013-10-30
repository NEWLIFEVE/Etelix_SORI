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
				'actions'=>array('index','view','GuardarListaTemp','GuardarListaFinal','delete', 'borrar','update','GuardarFac_RecTemp','GuardarFac_EnvTemp','GuardarPagoTemp','GuardarCobroTemp','BuscaFactura','GuardarDispRecibida'),
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccountingDocumentTemp']))
		{
			$model->attributes=$_POST['AccountingDocumentTemp'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,'lista_FacEnv'=>$lista_FacEnv,'lista_FacRec'=>$lista_FacRec,'lista_Pagos'=>$lista_Pagos,'lista_Cobros'=>$lista_Cobros,'lista_DispRec'=>$lista_DispRec
		));
	}
        /**
        * recibe los datos desde ajax y almacena solo las facturas enviadas doc temp...
        * @access public
        **/
        public function actionGuardarFac_RecTemp() 
        {
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
            $idGrupo.=NULL;
            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
            $idCarrierName.= Carrier::getName($idCarrier);
            $minutosTemp.= Utility::snull($minutos);
            $moneda.= Currency::getName($currency);
            
            $existeTemp= AccountingDocumentTemp::getExist($idCarrier, $numDocumento, $selecTipoDoc,$desdeFecha,$hastaFecha);  
            $existeFin= AccountingDocument::getExist($idCarrier, $numDocumento, $selecTipoDoc,$desdeFecha,$hastaFecha);  

            if($existeTemp==NULL&&$existeFin==NULL||$existeTemp==""&&$existeFin=="")
            {
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
                    $model->id_currency =$currency;
                    $model->id_carrier = $idCarrier;
                    $model->confirm = 1;

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
               if ($model->save()) {
                    $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                    Log::registrarLog($idAction, NULL, $model->id);

                    $params['idDoc'] = $model->id;
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
                    $params['ExisteTemp'] = $existeTemp;
                    $params['ExisteFin'] = $existeFin;
                    
                    echo json_encode($params);
                } 
            }else{
                    $params['idCarrierNameTemp'] = $idCarrierName;
                    $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                    $params['desdeFechaTemp'] =  $desdeFecha;
                    $params['hastaFechaTemp'] =  $hastaFecha;
                    $params['numDocumentoTemp'] = $numDocumento;
                    $params['ExisteTemp'] = $existeTemp;
                    $params['ExisteFin'] = $existeFin;
                    
                    echo json_encode($params);
            }
        }
        /**
        * recibe los datos desde ajax y almacena solo las facturas enviadas doc temp...
        * @access public
        **/
        public function actionGuardarFac_EnvTemp() 
        {
            $selecTipoDoc = $_GET['selecTipoDoc'];
            $idCarrier = $_GET['idCarrier'];
            $idGrupo = $_GET['idGrupo'];
            $fechaEmision = $_GET['fechaEmision'];
            $desdeFecha = $_GET['desdeFecha'];
            $hastaFecha = $_GET['hastaFecha'];
            $numDocumento = $_GET['numDocumento'];
            $minutos = $_GET['minutos'];
            $cantidad = $_GET['cantidad'];
            $currency = $_GET['currency'];
            $nota = $_GET['nota'];
            $idCarrierName = "";
            $selecTipoDocName = "";
            $minutosTemp = "";
            $moneda= "";
            $idGrupo.=NULL;
            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
            $idCarrierName.= Carrier::getName($idCarrier);
            $minutosTemp.= Utility::snull($minutos);
            $moneda.= Currency::getName($currency);
            
            $existeTemp= AccountingDocumentTemp::getExist($idCarrier, $numDocumento, $selecTipoDoc,$desdeFecha,$hastaFecha);  
            $existeFin= AccountingDocument::getExist($idCarrier, $numDocumento, $selecTipoDoc,$desdeFecha,$hastaFecha);  

            if($existeTemp==NULL&&$existeFin==NULL||$existeTemp==""&&$existeFin=="")
            {
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
                    $model->id_currency =$currency;
                    $model->id_carrier = $idCarrier;
                    $model->confirm = 0;
                    $model->valid_received_date = NULL;
                    $model->valid_received_hour = NULL;
                    $model->email_received_date = NULL;
                    $model->email_received_hour = NULL;

                if ($model->save()) {
                    $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                    Log::registrarLog($idAction, NULL, $model->id);

                    $params['idDoc'] = $model->id;
                    $params['idCarrierNameTemp'] = $idCarrierName;
                    $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                    $params['fechaEmisionTemp'] = $fechaEmision;
                    $params['desdeFechaTemp'] =  $desdeFecha;
                    $params['hastaFechaTemp'] =  $hastaFecha;
                    $params['fechaEnvioTemp'] = $fechaEmision;
                    $params['numDocumentoTemp'] = $model->doc_number;
                    $params['minutosTemp'] = $minutosTemp;
                    $params['cantidadTemp'] = $model->amount;
                    $params['currencyTemp'] = $moneda;

                    echo json_encode($params);
                }
            }else{
                    $params['idCarrierNameTemp'] = $idCarrierName;
                    $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                    $params['desdeFechaTemp'] =  $desdeFecha;
                    $params['hastaFechaTemp'] =  $hastaFecha;
                    $params['numDocumentoTemp'] = $numDocumento;
                    $params['ExisteTemp'] = $existeTemp;
                    $params['ExisteFin'] = $existeFin;
                    
                    echo json_encode($params);
            }
        }
        /**
        * recibe los datos desde ajax y almacena solo los pagos doc temp...
        * @access public
        **/
        public function actionGuardarPagoTemp() 
        {
            $selecTipoDoc = $_GET['selecTipoDoc'];
            $idGrupo = $_GET['idGrupo'];
            $fechaEmision = $_GET['fechaEmision'];
            $numDocumento = $_GET['numDocumento'];
            $cantidad = $_GET['cantidad'];
            $currency = $_GET['currency'];
            $nota = $_GET['nota'];
            $idGrupoName = "";
            $selecTipoDocName = "";
            $moneda= "";
            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
            $grupoCarrier = Carrier::getCarrierLeader($idGrupo);
            $idGrupoName.= CarrierGroups::getName($idGrupo);
            $moneda.= Currency::getName($currency);
             
            $model = new AccountingDocumentTemp;

                $model->id_type_accounting_document = $selecTipoDoc;
                $model->issue_date = Utility::snull($fechaEmision);
                $model->amount = Utility::snull($cantidad);
                $model->note = Utility::snull($nota);
                $model->id_carrier = $grupoCarrier;
                $model->id_currency =$currency;
                $model->doc_number = $numDocumento;
                $model->confirm = 1;
                $model->from_date = NULL;
                $model->to_date = NULL;
                $model->sent_date = NULL;
                $model->minutes = NULL;
                $model->valid_received_date = NULL;
                $model->valid_received_hour = NULL;
                $model->email_received_date = NULL;
                $model->email_received_hour = NULL;
                    
            if ($model->save()) {
                $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                Log::registrarLog($idAction, NULL, $model->id);
            
                $params['idDoc'] = $model->id;
                $params['idCarrierNameTemp'] = $idGrupoName;
                $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                $params['fechaEmisionTemp'] = $fechaEmision;
                $params['fechaEnvioTemp'] = $fechaEmision;
                $params['numDocumentoTemp'] = $model->doc_number;
                $params['cantidadTemp'] = $model->amount;
                $params['currencyTemp'] = $moneda;
                
                echo json_encode($params);
            }

        }
        /**
        * recibe los datos desde ajax y almacena solo los cobros doc temp...
        * @access public
        **/
        public function actionGuardarCobroTemp() 
        {
            $selecTipoDoc = $_GET['selecTipoDoc'];
            $idGrupo = $_GET['idGrupo'];
            $EmailfechaRecepcion = $_GET['EmailfechaRecepcion'];
            $numDocumento = $_GET['numDocumento'];
            $cantidad = $_GET['cantidad'];
            $currency = $_GET['currency'];
            $nota = $_GET['nota'];
            $selecTipoDocName = "";
            $valid_received_date = "";
            $idGrupoName = "";
            $moneda= "";
            $valid_received_date.=$EmailfechaRecepcion;
            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
            $grupoCarrier = Carrier::getCarrierLeader($idGrupo);
            $idGrupoName.= CarrierGroups::getName($idGrupo);
            $moneda.= Currency::getName($currency);
            
            $model = new AccountingDocumentTemp;
            
                $model->id_type_accounting_document = $selecTipoDoc;
                $model->valid_received_date = $valid_received_date;
                $model->amount = Utility::snull($cantidad);
                $model->note = Utility::snull($nota);
                $model->id_carrier = $grupoCarrier;
                $model->doc_number = $numDocumento;
                $model->id_currency =$currency;
                $model->confirm = 1;
                $model->to_date = NULL;
                $model->from_date = NULL;
                $model->sent_date = NULL;
                $model->issue_date = NULL;
                $model->email_received_hour = NULL;
                $model->valid_received_hour = NULL;
                $model->email_received_date = NULL;
               
            if ($model->save()) {
                $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                Log::registrarLog($idAction, NULL, $model->id);
            
                $params['idDoc'] = $model->id;
                $params['idCarrierNameTemp'] = $idGrupoName;
                $params['selecTipoDocNameTemp'] = $selecTipoDocName;
                $params['valid_received_dateTemp'] = $valid_received_date;
                $params['numDocumentoTemp'] = $model->doc_number;
                $params['cantidadTemp'] = $model->amount;
                $params['currencyTemp'] = $moneda;
                
                echo json_encode($params);
            }
        }
        /**
        * recibe los datos desde ajax y almacena solo las disputas recibidas doc temp...
        * @access public
        **/
        public function actionGuardarDispRecibida() 
        {
            $SelecTipoDoc = $_GET['selecTipoDoc'];
            $idCarrier = $_GET['idCarrier'];
            $DesdeFecha = $_GET['desdeFecha'];
            $HastaFecha = $_GET['hastaFecha'];
//            $idAccDocument = $_GET['numDocumento'];
            $idAccDocument =945;//esto es provisional
            $DestinoDispRec = $_GET['DestinoDispRec'];
            $MinutosEtelix = $_GET['minutos'];
            $MinutosProveedor = $_GET['minutosDocProveedor'];
            $MontoEtelix = $_GET['cantidad'];
            $MontoProveedor = $_GET['montoDocProveedor'];
            $Nota = $_GET['nota'];
            $idCarrierName = "";
            $facturaNumber = "";
            $selecTipoDocName = "";
            $monto = "";
            $destinoDispRecName = "";
            $monto.=$MinutosProveedor*$MontoProveedor;
            $selecTipoDocName.=TypeAccountingDocument::getName($SelecTipoDoc);//busca el name del tipo de documento, en este caso, disputas recibidas
            $idCarrierName.= Carrier::getName($idCarrier);//busca el name del carier
            $facturaNumber.=AccountingDocument::getDocNum($idAccDocument);//busca el numero de factura
            $destinoDispRecName.=Destination::getName($DestinoDispRec);//busca el name del destino
            $model = new AccountingDocumentTemp;
            
                $model->id_type_accounting_document = $SelecTipoDoc;
                $model->id_carrier = $idCarrier;
                $model->from_date = $DesdeFecha;
                $model->to_date = $HastaFecha;
                $model->id_destination = $DestinoDispRec;
                $model->amount = $monto;
                $model->min_etx = $MinutosEtelix;
                $model->min_carrier = $MinutosProveedor;
                $model->rate_etx = $MontoEtelix;
                $model->rate_carrier = $MontoProveedor;
                $model->note = Utility::snull($Nota);
                $model->id_accounting_document = $idAccDocument;
                $model->confirm = 1;
                $model->id_destination_supplier = NULL;
                $model->email_received_hour = NULL;
                $model->email_received_date = NULL;
                $model->valid_received_date = NULL;
                $model->valid_received_hour = NULL;
                $model->minutes = NULL;
                $model->sent_date = NULL;
                $model->issue_date = NULL;
                $model->id_currency =NULL;
                $model->doc_number = NULL;
                
               
            if ($model->save()) {
                $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
                Log::registrarLog($idAction, NULL, $model->id);
               
                $params['idDoc'] = $model->id;
                $params['idCarrierNameTemp']=$idCarrierName;
                $params['desdeFechaTemp']=$DesdeFecha;
                $params['hastaFechaTemp']=$HastaFecha;
                $params['numDocumentoTemp']=$facturaNumber;
                $params['minutosTemp'] =$MinutosEtelix;
                $params['MinutosProv'] =$MinutosProveedor;
                $params['TarifaEtx'] =$MontoEtelix;
                $params['TarifaProv'] =$MontoProveedor;
                $params['Destino'] =$destinoDispRecName;
                $params['cantidadTemp'] =$monto;
                
                echo json_encode($params);
            }
        }
        /*
        * este action no se usa, pero lo dejo hasta que no exista ningun problema en la nueva guardada
        **/
//        public function actionGuardarListaTemp() 
//        {
//            $selecTipoDoc = $_GET['selecTipoDoc'];
//            $idCarrier = $_GET['idCarrier'];
//            $idGrupo = $_GET['idGrupo'];
//            $fechaEmision = $_GET['fechaEmision'];
//            $desdeFecha = $_GET['desdeFecha'];
//            $hastaFecha = $_GET['hastaFecha'];
//            $EmailfechaRecepcion = $_GET['EmailfechaRecepcion'];
//            $EmailHoraRecepcion = $_GET['EmailHoraRecepcion'];
//            $numDocumento = $_GET['numDocumento'];
//            $minutos = $_GET['minutos'];
//            $cantidad = $_GET['cantidad'];
//            $currency = $_GET['currency'];
//            $nota = $_GET['nota'];
//            $idCarrierName = "";
//            $selecTipoDocName = "";
//            $valid_received_hour = "";
//            $valid_received_date = "";
//            $minutosTemp = "";
//            $moneda= "";
//            $selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);
//            $minutosTemp.= Utility::snull($minutos);
//            $moneda.= Currency::getName($currency);
////            $moneda.= Currency::getName($currency);
////            $existe=  AccountingDocumentTemp::getIfExist($idCarrier, $numDocumento, $selecTipoDoc);    
//            $model = new AccountingDocumentTemp;
//            $model->id_type_accounting_document = $selecTipoDoc;
//            $model->issue_date = Utility::snull($fechaEmision);
//            $model->from_date = Utility::snull($desdeFecha);
//            $model->to_date = Utility::snull($hastaFecha);
//            $model->sent_date = Utility::snull($fechaEmision);
//            $model->doc_number = $numDocumento;
//            $model->minutes = $minutosTemp;
//            $model->amount = Utility::snull($cantidad);
//            $model->note = Utility::snull($nota);
//            $model->id_currency =$currency;
//                $moneda.= Currency::getName($currency);
//                if ($selecTipoDoc == '3'||$selecTipoDoc == '4'){
//                    $idCarrierName.= CarrierGroups::getName($idGrupo);
//                    $grupoCarrier = Carrier::getCarrierLeader($idGrupo);
//                    $model->id_carrier = $grupoCarrier;
//                }else{
//                $model->id_carrier = $idCarrier;
//                $idCarrierName.= Carrier::getName($idCarrier);
//                }
//            if ($selecTipoDoc == '4') {
//                $model->email_received_hour = NULL;
//                $model->valid_received_hour = NULL;
//                $model->email_received_date = NULL;
//                $valid_received_date = $EmailfechaRecepcion;
//                $EmailfechaRecepcion = '';
//                $model->valid_received_date = $valid_received_date;
//            } 
//
//            if ($selecTipoDoc == '2') {
//            $fecha = strtotime($EmailfechaRecepcion);
//            $dia = date("N", $fecha);
//                if ($dia == 1 || $dia == 2) {
//
//                    if ($EmailHoraRecepcion >= '08:00' && $EmailHoraRecepcion <= '17:00') {
//
//                        $valid_received_date = $EmailfechaRecepcion;
//                        $valid_received_hour = $EmailHoraRecepcion;
//                        $model->valid_received_date = $valid_received_date;
//                        $model->valid_received_hour = $valid_received_hour;
//                        $model->email_received_date = $EmailfechaRecepcion;
//                        $model->email_received_hour = $EmailHoraRecepcion;
//                      
//                    } else {
//                        if($EmailHoraRecepcion < '08:00'){
//                            $valid_received_date = $EmailfechaRecepcion;
//                            $model->valid_received_date = $EmailfechaRecepcion;
//                        }else{
//                            $valid_received_date = $model->getValidDate($EmailfechaRecepcion, $dia);
//                            $model->valid_received_date = $valid_received_date;
//                        }
//                        $valid_received_hour = '08:00';
//                        $model->valid_received_hour = $valid_received_hour;
//                        $model->email_received_date = $EmailfechaRecepcion;
//                        $model->email_received_hour = $EmailHoraRecepcion;
//                    }
//                } else {
//
//                    $valid_received_date = $model->getValidDate($EmailfechaRecepcion, $dia);
//                    $valid_received_hour = '08:00';
//                    $model->valid_received_date = $valid_received_date;
//                    $model->valid_received_hour = $valid_received_hour;
//                    $model->email_received_date = $EmailfechaRecepcion;
//                    $model->email_received_hour = $EmailHoraRecepcion;
//                }
//            }         

//            if ($selecTipoDoc == '1'){
//                $model->confirm = 0;
//            }else{
//                $model->confirm = 1;
//            }

//            if ($model->save()) {
//                $idAction = LogAction::getLikeId('Crear Documento Contable Temp');
//                Log::registrarLog($idAction, NULL, $model->id);
            
//                $params['idDoc'] = $model->id;
//                $params['idCarrierNameTemp'] = $idCarrierName;
//                $params['selecTipoDocNameTemp'] = $selecTipoDocName;
//                $params['fechaEmisionTemp'] = $fechaEmision;
//                $params['desdeFechaTemp'] =  $desdeFecha;
//                $params['hastaFechaTemp'] =  $hastaFecha;
//                $params['EmailfechaRecepcionTemp'] = $EmailfechaRecepcion;
//                $params['EmailHoraRecepcionTemp'] = $EmailHoraRecepcion;
//                $params['valid_received_dateTemp'] = $valid_received_date;
//                $params['valid_received_hourTemp'] = $valid_received_hour;
//                $params['fechaEnvioTemp'] = $fechaEmision;
//                $params['numDocumentoTemp'] = $model->doc_number;
//                $params['minutosTemp'] = $minutosTemp;
//                $params['cantidadTemp'] = $model->amount;
//                $params['currencyTemp'] = $moneda;
//                
//                echo json_encode($params);
//            }
            
//    }
        
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
                $tipoID= '';
                $id_currency= '';
                $tipoID.= AccountingDocumentTemp::getTypeDoc($id);
                $id_currency.=Currency::getID($_POST['AccountingDocumentTemp']['id_currency']);
                if(isset($_POST['AccountingDocumentTemp']))
                {
                        $model->attributes=$_POST['AccountingDocumentTemp'];
                  
           switch ($tipoID) {
                
                case 1:   
                    $model->issue_date=Utility::snull($_POST['AccountingDocumentTemp']['issue_date']);
                    $model->from_date=Utility::snull($_POST['AccountingDocumentTemp']['from_date']);
                    $model->to_date=Utility::snull($_POST['AccountingDocumentTemp']['to_date']);
                    $model->sent_date=Utility::snull($_POST['AccountingDocumentTemp']['sent_date']);
                    $model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']);
                    $model->minutes=Utility::snull($_POST['AccountingDocumentTemp']['minutes']);
                    $model->amount=Utility::snull($_POST['AccountingDocumentTemp']['amount']);
-                   $model->id_currency=$id_currency;
                      break;
                  
                case 2:   
                    $model->issue_date=Utility::snull($_POST['AccountingDocumentTemp']['issue_date']);
                    $model->from_date=Utility::snull($_POST['AccountingDocumentTemp']['from_date']);
                    $model->to_date=Utility::snull($_POST['AccountingDocumentTemp']['to_date']);
                    $model->sent_date=Utility::snull($_POST['AccountingDocumentTemp']['sent_date']);
                    $model->email_received_date=Utility::snull($_POST['AccountingDocumentTemp']['email_received_date']);
                    $model->valid_received_date=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_date']);
                    $model->email_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['email_received_hour']);
                    $model->valid_received_hour=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_hour']);
                    $model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']);
                    $model->minutes=Utility::snull($_POST['AccountingDocumentTemp']['minutes']);
                    $model->amount=Utility::snull($_POST['AccountingDocumentTemp']['amount']);
-                   $model->id_currency=$id_currency;
                      break;
                  
                case 3:   
                    $model->issue_date=Utility::snull($_POST['AccountingDocumentTemp']['issue_date']);
                    $model->sent_date=NULL;
                    $model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']);
                    $model->amount=Utility::snull($_POST['AccountingDocumentTemp']['amount']);
-                   $model->id_currency=$id_currency;
                      break;
                  
                case 4:   
                    $model->valid_received_date=Utility::snull($_POST['AccountingDocumentTemp']['valid_received_date']);
                    $model->issue_date=NULL;
                    $model->from_date=NULL;
                    $model->to_date=NULL;
                    $model->email_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->valid_received_hour=NULL;
                    $model->minutes=NULL;
                    $model->sent_date=NULL;
                    $model->doc_number=Utility::snull($_POST['AccountingDocumentTemp']['doc_number']);
                    $model->amount=Utility::snull($_POST['AccountingDocumentTemp']['amount']);
-                   $model->id_currency=$id_currency;
                      break;

           } 
                        if($model->save())
                                return "Actualizado id: ".$model->id;
                        else
                                return "Algo salio mal";
                }
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
        /**
         * esta funcion busca la factura para disputas, 
         * se le pasa el carrier, inicio de periodo a facturar y el fin de periodo a facturar
         */
        public function actionBuscaFactura() 
        {
            $CarrierDisp = $_GET['CarrierDisp'];
            $desdeDisp = $_GET['desdeDisp'];
            $hastaDisp = $_GET['hastaDisp']; 
        
            $factura=AccountingDocument::getFacDispRec($CarrierDisp,$desdeDisp,$hastaDisp);
              $facturasFin=implode(',',$factura);  
                echo $facturasFin;  
        }
}
