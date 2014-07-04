<?php

class AccountingDocumentController extends Controller
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
				'actions'=>array('index','view','Confirmar','Borrar','buscadatos','UpdateDisputa','AdminDispute','GetDispute','UpdateStatusDisputa'),
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
		$model=new AccountingDocument;
                $lista=AccountingDocument::listaFacturasEnviadas();
//                $lista=AccountingDocument::listaFacturasEnviadas(Yii::app()->user->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AccountingDocument']))
		{
			$model->attributes=$_POST['AccountingDocument'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,'lista'=>$lista
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
		$dataProvider=new CActiveDataProvider('AccountingDocument');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AccountingDocument('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AccountingDocument']))
			$model->attributes=$_GET['AccountingDocument'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AccountingDocument the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AccountingDocument::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AccountingDocument $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='accounting-document-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
	/**
	 */
	public function actionConfirmar($id)
	{
//	          $id=  AccountingDocument::getConfirmID(0); 
                  $model=$this->loadModel($id);
        	  $model->confirm=1;
                  $model->save();
			if($model->save()){
                            echo 'guardo';
                        }
	}
	public function actionUpdateDisputa($id)
	{
//	          $id=  AccountingDocument::getConfirmID(0); 
                  $model=$this->loadModel($id);
//        	  $model->amount=Utility::changePositive($_POST['AccountingDocumentTemp']['amount_aproved']);
        	  $model->amount=$_POST['AccountingDocumentTemp']['amount_aproved'];
                  $model->save();
			if($model->save()){
                            echo 'guardo';
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

                if(isset($_POST['AccountingDocument']))
                {
                    
                        $model->attributes=$_POST['AccountingDocument'];
//                        $model->id_type_accounting_document=TypeAccountingDocument::getId($_POST['AccountingDocumentTemp']['id_type_accounting_document']);
//                        $model->id_carrier=Carrier::getId($_POST['AccountingDocumentTemp']['id_type_accounting_document']);
                              
                $model->issue_date=Utility::snull($_POST['AccountingDocument']['issue_date']);
                $model->from_date=Utility::snull($_POST['AccountingDocument']['from_date']);
                $model->to_date=Utility::snull($_POST['AccountingDocument']['to_date']);
                $model->email_received_date=Utility::snull($_POST['AccountingDocument']['email_received_date']);
                $model->valid_received_date=Utility::snull($_POST['AccountingDocument']['valid_received_date']);
                $model->email_received_hour=Utility::snull($_POST['AccountingDocument']['email_received_hour']);
                $model->valid_received_hour=Utility::snull($_POST['AccountingDocument']['valid_received_hour']);
                $model->sent_date=Utility::snull($_POST['AccountingDocument']['sent_date']);
                $model->doc_number=Utility::snull($_POST['AccountingDocument']['doc_number']);
                $model->minutes=Utility::snull($_POST['AccountingDocument']['minutes']);
                $model->amount=Utility::snull($_POST['AccountingDocument']['amount']);
                 $id_currency=Currency::getID($_POST['AccountingDocument']['id_currency']);
-               $model->id_currency=$id_currency;
                        if($model->save())
                                return "Actualizado id: ".$model->id;
                        else
                                return "Algo salio mal";
                }
        }
         /**
         * eliminar a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @access public
         * @param integer $id the ID of the model to be delete
         */
        public function actionBorrar($id)
	{
		$this->loadModel($id)->delete();
	}
         /**
         *busca los numeros de documentos con el id provenientes de la vista de confirmar
         */
        public function actionbuscadatos()
        {
               $id = explode(',', $_GET['datos']);
               $numDocument='';
                foreach($id as $key => $value)
                {
                    $numDocument.=  AccountingDocument::getDocNum($id);
//                    $params['numDocument'] = $numDocument;
                }
               echo 'numero de factura:'.$numDocument.'<p>';
//                echo json_encode($params);
        }
        /**
         * 
         */
        public function actionAdminDispute()
	{
                $model=new AccountingDocument;
		$this->render('adminDispute',array('model'=>$model));
	}
        public function actionGetDispute()
	{
                if($_GET["AccountingDocument"]["from_date"]!="" || $_GET["AccountingDocument"]["to_date"]!=""){
                    $fromDate=$_GET["AccountingDocument"]["from_date"];
                    $toDate=$_GET["AccountingDocument"]["to_date"];
                }else{
                    $fromDate=NULL;
                    $toDate=NULL;
                }
                $modelDispute=  AccountingDocument::getDispute($_GET["AccountingDocument"]["id_carrier"],$fromDate,$toDate);
                if($modelDispute!=NULL){
                    $bodyDisputes= "<h3>Disputas sin notas de credito asociadas a: <font style='color:rgba(111,204,187,1);'>".Carrier::getName($_GET['AccountingDocument']['id_carrier'])."</font></h3>";
                    $bodyDisputes.="<table border='1' class='tablaVistDocTemporales lista_Disp_NotaCEnv'>
                                        <tr>
                                           <td> Tipo de Disputa </td>
                                           <td> Monto </td>
                                           <td> fecha inicio </td>
                                           <td> fecha fin </td>
                                           <td> N° de factura </td>
                                           <td> Min. Etx </td>
                                           <td> Min. Carrier </td>
                                           <td> Tarifa Etx </td>
                                           <td> Tarifa Carrier </td>
                                           <td> status </td>
                                        </tr>";
                    foreach ($modelDispute as $key => $dispute) 
                    {
//                        
                        $bodyDisputes.="<tr class='vistaTemp' id='{$dispute->id}'>
                                           <td> {$dispute->type_dispute} </td>
                                           <td> ".Utility::format_decimal($dispute->amount)." </td>
                                           <td> {$dispute->from_date} </td>
                                           <td> {$dispute->to_date} </td>
                                           <td> {$dispute->doc_number} </td>
                                           <td> ".Utility::format_decimal($dispute->min_etx)." </td>
                                           <td> ".Utility::format_decimal($dispute->min_carrier)." </td>
                                           <td> {$dispute->rate_etx} </td>
                                           <td> {$dispute->rate_carrier} </td>
                                           <td> ".$this->defineConfirmDispute($dispute->confirm)."</td>
                                        </tr>";                        
                    }
                    $bodyDisputes.="</table>";
                    echo $bodyDisputes;
                }else{
                    echo 0;
                }
	}
        /**
         * 
         * @param type $confirm
         * @return type
         */
        public function defineConfirmDispute($confirm)
        {
            if($confirm==1)
                return "<select id='statusDispute' name='statusDispute' style='background:rgba(111,204,187,1);'><option value='{$confirm}'> Procede </option> <option value='-1'> No Procede </option></select>";
            else    
                return "<select id='statusDispute' name='statusDispute' style='background:rgb(226, 168, 140);'><option value='{$confirm}'> No Procede </option> <option value='1'> Procede </option></select>";
        }
        /**
         * 
         * @param type $id
         */
        public function actionUpdateStatusDisputa($id)
	{
                $model=$this->loadModel($id);
                $model->confirm=$_POST['statusDispute'];
                $model->save();
                if($model->save()){
                    echo 'guardo';
                }
	}
}
