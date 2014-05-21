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
				'actions'=>array('index','view','DynamicDatosContrato','Contrato','ContratoConfirma','Comprueba_BankFee','UpdateStartDateCTP','UpdateStartDateCTPS'),
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
            $params['carrierName']=Carrier::getName($_GET['Contrato']['id_carrier']);  
            $params['companyName']=Company::getName($_GET['Contrato']['id_company']);    
            $params['termino_pName']=TerminoPago::getName($_GET['Contrato']['id_termino_pago']);  
            $params['termino_p_supp_Name']=TerminoPago::getName($_GET['Contrato']['id_termino_pago_supplier']);    
            $params['monetizableName']=Monetizable::getName($_GET['Contrato']['id_monetizable']);  
            $params['divide_fact_Name']=self::filtraVariablesNull($_GET['divide_fact'],"Si","No");
            $params['fact_period_Name']=self::defineOcultosyNull("periodo",$_GET['Contrato']['id_fact_period']);
            $params['dia_ini_fact_Name']=self::defineOcultosyNull("dia",$_GET['dia_ini_fact']); 
            $params['bank_feeName']=self::filtraVariablesNull($_GET['Contrato']['bank_fee'],"Si","No"); 
            $params['Contrato_upConfirma']=self::filtraVariablesNull($_GET['Contrato']['up'],"Presidencia","Ventas"); 
            $params['Contrato_statusConfirma']=self::filtraVariablesNull($_GET['Contrato']['status'],"Activo","Inactivo");    
            $params['termino_pNameO']=self::defineOcultosyNull("tp",$_GET['TerminoP_Oculto']); 
            $params['termino_p_supp_NameO']=self::defineOcultosyNull("tpO",$_GET['TerminoP_supplier_Oculto']);  
            $params['monetizableNameO']=self::defineOcultosyNull("monetizableO",$_GET['monetizable_Oculto']);
            $params['divide_fact_NameO']=self::filtraVariablesNull($_GET['divide_fact_Oculto'],"Si","No");
            $params['fact_period_NameO']=self::defineOcultosyNull("periodoO",$_GET['id_fact_period_Oculto']);
            $params['dia_ini_fact_NameO']=self::defineOcultosyNull("diaO",$_GET['dia_ini_fact_Oculto']); 
            $params['bank_feeNameO']=self::filtraVariablesNull($_GET['bank_feeOculto'],"Si","No");
            $params['Contrato_upConfirmaO']=self::filtraVariablesNull($_GET['Contrato_upOculto'],"Presidencia","Ventas");     
            $params['Contrato_statusConfirmaO']=self::filtraVariablesNull($_GET['Contrato_statusOculto'],"Activo","Inactivo");
            echo json_encode($params);
    }
    
    public function filtraVariablesNull($var,$opcionA,$opcionB)
    {
         if($var!=NULL||$var!="")  {          
            if ($var==1)      $var_name=$opcionA;
            else if($var==0)  $var_name=$opcionB;
        }else{
            $var_name="";
        }
        return $var_name;
    }
    public function defineDiaSemana($id_dia)
    {
        switch ($id_dia) {
            case 1:
            return "Lunes";
                break;
            case 2:
            return "Martes";
                break;
            case 3:
            return "Miercoles";
                break;
            case 4:
            return "Jueves";
                break;
            case 5:
            return "Viernes";
                break;
            case 6:
            return "Sabado";
                break;
            case 7:
            return "Domingo";
                break;
            default:
                return "Lunes";
                break;
        }
    }
    
    public function defineOcultosyNull($input,$var)
    {
        if ($var==""||$var==NULL){
            return "";
        }else{
            switch ($input) {
                 case "periodo":
                      return FactPeriod::getName($var);
                     break;
                 case "dia":
                     return self::defineDiaSemana($var);   
                     break;
                 case "tpO":
                     return TerminoPago::getName($var);
                     break;
                 case "monetizableO":
                     return Monetizable::getName($var);
                     break;
                 case "periodoO":
                     return FactPeriod::getName($var);
                     break;
                 case "diaO":
                     return self::defineDiaSemana($var);
                     break;
                 default:
                     return "";
                     break;
                 } 
        }
    }
        public function actionUpdateStartDateCTP()
        {
            $start_date_termino_pago=$_GET["Contrato"]['start_date_TP_customer'];
            $modelContrat = Contrato::DatosContrato($_GET["Contrato"]["id_carrier"]);
            if($modelContrat!=null)
            {
                $modelCTP=ContratoTerminoPago::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelContrat->id)); 
                if($modelCTP!=NULL){
                            $modelCTP->start_date=  Utility::snull($start_date_termino_pago);
                            if($modelCTP->save()){Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTP->id);
                                echo $modelCTP->start_date;
                        }
                }else{
                    echo"fail";
                }
            }
        }
        public function actionUpdateStartDateCTPS()
        {
            $start_date_termino_pago_supplier=$_GET["Contrato"]['start_date_TP_supplier'];
            $modelContrat = Contrato::DatosContrato($_GET["Contrato"]["id_carrier"]);
            if($modelContrat!=null)
            {
                $modelCTPS=  ContratoTerminoPagoSupplier::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelContrat->id)); 
                if($modelCTPS!=NULL){
                            $modelCTPS->start_date= Utility::snull($start_date_termino_pago_supplier);
                            if($modelCTPS->save()){Log::registrarLog(LogAction::getId('Modificar TerminoPago Supplier'),NULL, $modelCTPS->id);
                                echo $modelCTPS->start_date;
                            }
                }else{
                    echo"fail";
                }
            }
        }
	public function actionContrato()
	{
            $model=new Contrato;
                $carrier=$_GET['Contrato']['id_carrier'];
                $divide_fact=$_GET['divide_fact'];
                $fact_period=$_GET['Contrato']['id_fact_period'];
                $dia_ini_fact=$_GET['dia_ini_fact'];
                $sign_date=  Utility::snull($_GET["Contrato"]['sign_date']);
                $production_date=Utility::snull($_GET["Contrato"]['production_date']);
                $end_date=Utility::snull($_GET["Contrato"]['end_date']);
                $company=$_GET["Contrato"]['id_company'];
                $termino_pago=$_GET["Contrato"]['id_termino_pago'];
//                $start_date_termino_pago=$_GET["Contrato"]['start_date_TP_customer'];
                $termino_pago_supplier=$_GET["Contrato"]['id_termino_pago_supplier'];
//                $start_date_termino_pago_supplier=$_GET["Contrato"]['start_date_TP_supplier'];
                $monetizable=$_GET["Contrato"]['id_monetizable'];
                $dias_disputa=$_GET["Contrato"]['id_disputa'];
                $dias_disputa_solved=$_GET["Contrato"]['id_disputa_solved'];
                $credito=$_GET["Contrato"]['id_limite_credito'];
                $compra=$_GET["Contrato"]['id_limite_compra'];
                $Contrato_up=$_GET['Contrato']['up'];
                $Contrato_status=$_GET['Contrato']['status'];
                $bank_fee=$_GET['Contrato']['bank_fee'];

                $modelAux=Contrato::model()->find('id_carrier=:carrier',array(':carrier'=>$carrier));
                $modelCarrier=  Carrier::model()->find('id=:id_carrier',array(':id_carrier'=>$carrier));
                $modelGroup= Carrier::model()->findAll('id_carrier_groups=:groups',array(':groups'=>$modelCarrier->id_carrier_groups));
                if($fact_period==null ||$fact_period==""){
                    $fact_periodNA= FactPeriod::getModelName("No Aplica");
                    $fact_period=$fact_periodNA->id;
                } 
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
                            if ($Contrato_up != $modelAux->up){
                            $modelAux->up=$Contrato_up;
                            Log::registrarLog(LogAction::getId('Modificar UP'),NULL, $modelAux->id);
                            }
                            if ($bank_fee != $modelAux->bank_fee){
                            $modelAux->bank_fee=$bank_fee;
                            }
                            $modelAux->end_date=$end_date;

                             if ($Contrato_status!='' || $Contrato_status!=NULL){
                                if($modelCarrier!=NULL){
                                    if($modelCarrier->status != $Contrato_status){
                                        $modelCarrier->status = $Contrato_status;
                                        if($modelCarrier->save())Log::registrarLog(LogAction::getId('Modificar Status Carrier'),NULL,$carrier);
                                    }
                                }
                            }
                             if($modelGroup!==NULL||$modelGroup!==FALSE){
                                 foreach ($modelGroup as $key => $group) 
                                 {
                                    $modelContBankFee= Contrato::model()->find('id_carrier=:carrier AND end_date IS NULL',array(':carrier'=>$group->id));
                                    if($modelContBankFee!=NULL ||$modelContBankFee!=FALSE){
                                        if ($bank_fee != $modelContBankFee->bank_fee){
                                            $modelContBankFee->bank_fee=$bank_fee;  
                                            $modelContBankFee->save();     
                                        } 
                                    }
                                 }
                             }
                            /*TERMINO PAGO CLIENTE*/         
                            $modelCTP=ContratoTerminoPago::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                            if($modelCTP!=NULL){
                                if($modelCTP->id_termino_pago != $termino_pago){
                                    $modelCTP->end_date=date('Y-m-d'); 
                                    $modelCTP->save();
                                    $modelCTPNEW = new ContratoTerminoPago;
                                    $modelCTPNEW->id_contrato=$modelAux->id;
                                    $modelCTPNEW->start_date=date('Y-m-d');
                                    $modelCTPNEW->id_termino_pago =$termino_pago;
                                    if($modelCTPNEW->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTPNEW->id);
//                                }else{
//                                    if($modelCTP->start_date != $start_date_termino_pago && $start_date_termino_pago!=NULL){
//                                        $modelCTP->start_date=  Utility::snull($start_date_termino_pago);
//                                        if($modelCTP->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTP->id);
//                                    }
                                }
                            }else{
                                    $modelCTPNEW = new ContratoTerminoPago;
                                    $modelCTPNEW->id_contrato=$modelAux->id;
                                    $modelCTPNEW->start_date=date('Y-m-d');
                                    $modelCTPNEW->id_termino_pago =$termino_pago;
                                    if($modelCTPNEW->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago'),NULL, $modelCTPNEW->id);
                            }

                            /*TERMINO PAGO PROVEEDOR*/

                            $modelCTPsupplier=ContratoTerminoPagoSupplier::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                            if($modelCTPsupplier!=NULL){
                                if($modelCTPsupplier->id_termino_pago_supplier != $termino_pago_supplier){
                                    $modelCTPsupplier->end_date=date('Y-m-d'); 
                                    $modelCTPsupplier->save();
                                    $modelCTPSNEW = new ContratoTerminoPagoSupplier;
                                    $modelCTPSNEW->id_contrato=$modelAux->id;
                                    $modelCTPSNEW->start_date=date('Y-m-d');
                                    $modelCTPSNEW->id_termino_pago_supplier =$termino_pago_supplier;
                                    $modelCTPSNEW->month_break =Utility::snull($divide_fact);
                                    $modelCTPSNEW->first_day =Utility::snull($dia_ini_fact);
                                    $modelCTPSNEW->id_fact_period =Utility::snull($fact_period);
                                    if($modelCTPSNEW->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago Supplier'),NULL, $modelCTPSNEW->id);

                                }else{
//                                    if($modelCTPsupplier->start_date != $start_date_termino_pago_supplier && $start_date_termino_pago_supplier!=NULL)
//                                        $modelCTPsupplier->start_date=  Utility::snull($start_date_termino_pago_supplier);
                                    $modelCTPsupplier->month_break =Utility::snull($divide_fact);
                                    $modelCTPsupplier->first_day =Utility::snull($dia_ini_fact);
                                    $modelCTPsupplier->id_fact_period =Utility::snull($fact_period);
                                    if($modelCTPsupplier->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago Supplier'),NULL, $modelCTPsupplier->id);
                                }
                            }else{
                                    $modelCTPSNEW = new ContratoTerminoPagoSupplier;
                                    $modelCTPSNEW->id_contrato=$modelAux->id;
                                    $modelCTPSNEW->start_date=date('Y-m-d');
                                    $modelCTPSNEW->id_termino_pago_supplier =$termino_pago_supplier;
                                    $modelCTPSNEW->month_break =Utility::snull($divide_fact);
                                    $modelCTPSNEW->first_day =Utility::snull($dia_ini_fact);
                                    $modelCTPSNEW->id_fact_period =Utility::snull($fact_period);
                                    if($modelCTPSNEW->save())Log::registrarLog(LogAction::getId('Modificar TerminoPago Supplier'),NULL, $modelCTPSNEW->id);
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
                                    if($modelCMNEW->save())Log::registrarLog(LogAction::getId('Modificar Monetizable'),NULL, $modelCMNEW->id);
                                }
                            }else{
                                    $modelCMNEW = new ContratoMonetizable;
                                    $modelCMNEW->id_contrato=$modelAux->id;
                                    $modelCMNEW->start_date=date('Y-m-d');
                                    $modelCMNEW->id_monetizable =$monetizable;
                                    if($modelCMNEW->save())Log::registrarLog(LogAction::getId('Modificar Monetizable'),NULL, $modelCMNEW->id);
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
                                    if($modelCDNEW->save())Log::registrarLog(LogAction::getId('Modificar Dias Max Disputa'),NULL, $modelCDNEW->id);
                                }
                            }else{
                                    $modelCDNEW = new DaysDisputeHistory;
                                    $modelCDNEW->id_contrato=$modelAux->id;
                                    $modelCDNEW->start_date=date('Y-m-d');
                                    $modelCDNEW->days =$dias_disputa;
                                    if($modelCDNEW->save())Log::registrarLog(LogAction::getId('Modificar Dias Max Disputa'),NULL, $modelCDNEW->id);
                            }
                            /*DIAS_DISPUTA_SOLVED*/
                            $modelCDS= SolvedDaysDisputeHistory::model()->find('id_contrato=:contrato and end_date IS NULL',array(':contrato'=>$modelAux->id)); 
                            if($modelCDS!=NULL){
                                if($modelCDS->days != $dias_disputa_solved){
                                    $modelCDS->end_date=date('Y-m-d'); 
                                    $modelCDS->save();
                                    $modelCDSNEW = new SolvedDaysDisputeHistory;
                                    $modelCDSNEW->id_contrato=$modelAux->id;
                                    $modelCDSNEW->start_date=date('Y-m-d');
                                    $modelCDSNEW->days =$dias_disputa_solved;
                                    if($modelCDSNEW->save())Log::registrarLog(LogAction::getId('Modificar Dias Solventar Disputa'),NULL, $modelCDSNEW->id);
                                }
                            }else{
                                    $modelCDSNEW = new SolvedDaysDisputeHistory;
                                    $modelCDSNEW->id_contrato=$modelAux->id;
                                    $modelCDSNEW->start_date=date('Y-m-d');
                                    $modelCDSNEW->days =$dias_disputa_solved;
                                    if($modelCDSNEW->save())Log::registrarLog(LogAction::getId('Modificar Dias Solventar Disputa'),NULL, $modelCDSNEW->id);
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
                                    if($modelCLCreditoNEW->save())Log::registrarLog(LogAction::getId('Modificar Limite de Credito'),NULL, $modelCLCreditoNEW->id);
                                }
                            }else{
                                    $modelCLCreditoNEW = new CreditLimit;
                                    $modelCLCreditoNEW->id_contrato=$modelAux->id;
                                    $modelCLCreditoNEW->start_date=date('Y-m-d');
                                    $modelCLCreditoNEW->amount =$credito;
                                    if($modelCLCreditoNEW->save())Log::registrarLog(LogAction::getId('Modificar Limite de Credito'),NULL, $modelCLCreditoNEW->id);
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
                                    if($modelCLCompraNEW->save())Log::registrarLog(LogAction::getId('Modificar Limite de Compra'),NULL, $modelCLCompraNEW->id); 
                                }
                            }else{
                                    $modelCLCompraNEW = new PurchaseLimit;
                                    $modelCLCompraNEW->id_contrato=$modelAux->id;
                                    $modelCLCompraNEW->start_date=date('Y-m-d');
                                    $modelCLCompraNEW->amount =$compra;
                                    if($modelCLCompraNEW->save())Log::registrarLog(LogAction::getId('Modificar Limite de Compra'),NULL, $modelCLCompraNEW->id);
                            }
                            $modelAux->save();      
                    }else{
                        /*NUEVO CONTRATO*/
                            $model->id_company=$company;
                            $model->id_carrier=$carrier;
                            $model->sign_date=$sign_date;
                            $model->production_date=$production_date;
                            $model->end_date=NULL;
                            $model->up=$Contrato_up;
                            $model->bank_fee=$bank_fee;

                            if($model->save()){ 
                            Log::registrarLog(LogAction::getId('Crear Contrato'),NULL, $model->id);
                            /*BANCK FEE PARA LOS DEMAS CARRIERS DEL GRUPO*/
                            if($modelGroup!==NULL||$modelGroup!==FALSE){
                                 foreach ($modelGroup as $key => $group) 
                                 {
                                    $modelContBankFee= Contrato::model()->find('id_carrier=:carrier AND end_date IS NULL',array(':carrier'=>$group->id));
                                    if($modelContBankFee!=NULL ||$modelContBankFee!=FALSE){ 
                                        if($modelContBankFee->id != $model->id_carrier){
                                            $modelContBankFee->bank_fee=$bank_fee;  
                                            $modelContBankFee->save(); 
                                         }
                                    }
                                 }
                            }
                            /*STATUS CARRIER*/
                            if ($Contrato_status!='' || $Contrato_status!=NULL){
                                if($modelCarrier!=NULL){
                                    if($modelCarrier->status != $Contrato_status){
                                        $modelCarrier->status = $Contrato_status;
                                        if($modelCarrier->save())Log::registrarLog(LogAction::getId('Modificar Status Carrier'),NULL,$carrier);
                                    }
                                }
                            }
                            /*TERMINO PAGO CLIENTES*/
                            if($termino_pago!='' || $termino_pago!=NULL){
                            $modelCTPNEW = new ContratoTerminoPago;
                            $modelCTPNEW->id_contrato=$model->id;
                            $modelCTPNEW->start_date=date('Y-m-d');
                            $modelCTPNEW->id_termino_pago =$termino_pago;
                            $modelCTPNEW->save();
                            }
                            /*TERMINO PAGO PROVEDORES*/
                            if($termino_pago_supplier!='' || $termino_pago_supplier!=NULL){
                            $modelCTPSNEW = new ContratoTerminoPagoSupplier;
                            $modelCTPSNEW->id_contrato=$model->id;
                            $modelCTPSNEW->start_date=date('Y-m-d');
                            $modelCTPSNEW->id_termino_pago_supplier =$termino_pago_supplier;
                            $modelCTPSNEW->month_break =Utility::snull($divide_fact);
                            $modelCTPSNEW->first_day =Utility::snull($dia_ini_fact);
                            $modelCTPSNEW->id_fact_period =Utility::snull($fact_period);
                            $modelCTPSNEW->save();
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
                            /*DIAS_DISPUTA_SOLVED*/
                            if($dias_disputa_solved!='' || $dias_disputa_solved!=NULL){
                            $modelCDNEW = new SolvedDaysDisputeHistory;
                            $modelCDNEW->id_contrato=$model->id;
                            $modelCDNEW->start_date=date('Y-m-d');
                            $modelCDNEW->days =$dias_disputa_solved;
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
                    }                   
            var_dump ("guardo ;)");
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
                $params['start_date_TP_customer']= Utility::snull(ContratoTerminoPago::getModel($model->id)->start_date);
                $params['termino_pago_supplier']= ContratoTerminoPagoSupplier::getTpId_Element($model->id,"id_termino_pago_supplier");
                $params['start_date_TP_supplier']=  Utility::snull(ContratoTerminoPagoSupplier::getTpId_Element($model->id,"start_date"));
                $params['fact_period']= ContratoTerminoPagoSupplier::getTpId_Element($model->id,"id_fact_period");
                $params['divide_fact']= ContratoTerminoPagoSupplier::getTpId_Element($model->id,"month_break");
                $params['dia_ini_fact']=ContratoTerminoPagoSupplier::getTpId_Element($model->id,"first_day");
                $params['monetizable']=ContratoMonetizable::getMonetizableId($model->id);
                $params['manager']= Managers::getName(CarrierManagers::getIdManager($model->id_carrier));
                $params['Contrato_up']=Contrato::getUP($_GET['idCarrier']);
                $params['bank_fee']=Contrato::getBankFee($_GET['idCarrier']);
                $params['Contrato_status']=Carrier::getStatus($_GET['idCarrier']);
                $params['dias_disputa']= DaysDisputeHistory::getDays($model->id);
                $params['dias_disputa_solved']= SolvedDaysDisputeHistory::getDays($model->id);
                $params['carrier']= Carrier::getName($model->id_carrier);
                $params['credito']= CreditLimit::getCredito($model->id);
                $params['compra']= PurchaseLimit::getCompra($model->id);
                $params['fechaManager']=CarrierManagers::getFechaManager($model->id_carrier);
           }else{
                $params['company']=''; 
                $params['sign_date']='';
                $params['production_date']='';
                $params['termino_pago']='';
                $params['start_date_TP_customer']='';
                $params['termino_pago_supplier']='';
                $params['start_date_TP_supplier']='';
                $params['fact_period']='';
                $params['divide_fact']='';
                $params['dia_ini_fact']='';
                $params['monetizable']='';
                $params['manager']=Managers::getName(CarrierManagers::getIdManager($_GET['idCarrier']));
                $params['Contrato_up']='';
                $params['bank_fee']='';
                $params['Contrato_status']='';
                $params['dias_disputa']='';
                $params['credito']='';
                $params['compra']='';
                $params['carrier']= Carrier::getName($_GET['idCarrier']);
                $params['fechaManager']=CarrierManagers::getFechaManager($_GET['idCarrier']);
           }
           echo json_encode($params);
        } 
        
        public function actionComprueba_BankFee()
        {
            $modelGroup=  Carrier::model()->find('id_carrier_groups=:groups',array(':groups'=>$_GET['id_group']));       
            $bank_fee= Contrato::model()->find('id_carrier=:carrier and bank_fee=1 and end_date IS NULL',array(':carrier'=>$modelGroup->id));
            if($bank_fee!=NULL)echo $bank_fee->bank_fee;  
              else             echo "0";
        }

}