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
				'actions'=>array('index','view','GuardarListaTemp','GuardarListaFinal','delete'),
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
	public function actionGuardarListaTemp()
	{
		$selecTipoDoc=$_GET['selecTipoDoc'];
		$idCarrier=$_GET['idCarrier'];
		$fechaEmision=$_GET['fechaEmision'];
		$desdeFecha=$_GET['desdeFecha'];
		$hastaFecha=$_GET['hastaFecha'];
		$fechaRecepcion=$_GET['fechaRecepcion'];
		$fechaEnvio=$_GET['fechaEnvio'];
		$numDocumento=$_GET['numDocumento'];
		$minutos=$_GET['minutos'];
		$cantidad=$_GET['cantidad'];
		$nota=$_GET['nota'];
		$idCarrierName="";
		$selecTipoDocName="";
		$idCarrierName.= Carrier::getName($idCarrier);
		$selecTipoDocName.=TypeAccountingDocument::getName($selecTipoDoc);

		$model=new AccountingDocumentTemp;
        $model->id_type_accounting_document = $selecTipoDoc;
        $model->id_carrier = $idCarrier;
        $model->issue_date =$fechaEmision;
        $model->from_date = $desdeFecha;
        $model->to_date = $hastaFecha;
        $model->received_date = $fechaRecepcion;
        $model->sent_date = $fechaEnvio;
        $model->doc_number = $numDocumento;
        $model->minutes = $minutos;
        $model->amount = $cantidad;
        $model->note = $nota;
     
	    if($model->save())
	    {
	    	$idAction=LogAction::getLikeId('Crear Documento Contable Temp');
	    	Log::registrarLog($idAction, NULL, $model->id);
	    	$params['idDoc']=$model->id;
	    	$params['idCarrierNameTemp']=$idCarrierName;
	    	$params['selecTipoDocNameTemp']=$selecTipoDocName;
	    	$params['fechaEmisionTemp']=$fechaEmision;
	    	$params['desdeFechaTemp']=$desdeFecha;
	    	$params['hastaFechaTemp']=$hastaFecha;
	    	$params['fechaRecepcionTemp']=$fechaRecepcion;
	    	$params['fechaEnvioTemp']=$fechaEnvio;
	    	$params['numDocumentoTemp']=$numDocumento;
	    	$params['minutosTemp']=$minutos;
	    	$params['cantidadTemp']=$cantidad;
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

		if(isset($_POST['AccountingDocumentTemp']))
		{
			$model->attributes=$_POST['AccountingDocumentTemp'];
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
	 * @access public
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
