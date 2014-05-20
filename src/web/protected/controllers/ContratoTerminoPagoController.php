<?php

class ContratoTerminoPagoController extends Controller
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
				'actions'=>array('index','view','PaymentTermHistory','DeleteCTP'),
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
		$model=new ContratoTerminoPago;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContratoTerminoPago']))
		{
			$model->attributes=$_POST['ContratoTerminoPago'];
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
            var_dump($id);
            $model=$this->loadModel($id);     
            if(isset($_POST['Contrato']))
            {
                if(isset($_POST['Contrato']['id_termino_pago']))$model->id_termino_pago=Utility::snull(TerminoPago::getId($_POST['Contrato']['id_termino_pago'])->id); 
                if(isset($_POST['Contrato']['start_date']))$model->start_date=Utility::snull($_POST['Contrato']['start_date']); 
                if(isset($_POST['Contrato']['end_date']))$model->end_date=Utility::snull($_POST['Contrato']['end_date']); 

                if($model->save())
                {
                    Log::registrarLog(LogAction::getId('Modificar Historial TerminoPago'),NULL, $id);
                    echo "Actualizado id: ".$model->id;
                }
                else
                {
                    echo "Algo salio mal";
                }
            }
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
		$dataProvider=new CActiveDataProvider('ContratoTerminoPago');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ContratoTerminoPago('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ContratoTerminoPago']))
			$model->attributes=$_GET['ContratoTerminoPago'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ContratoTerminoPago the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ContratoTerminoPago::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ContratoTerminoPago $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='contrato-termino-pago-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        public function actionPaymentTermHistory()
        {
            $modelContrat = Contrato::DatosContrato($_GET["Contrato"]["id_carrier"]);
            if($modelContrat!=null)
            {
                $modelpaymentTerm= ContratoTerminoPago::paymentTermHistory($_GET["Contrato"]["id_carrier"]);
                if($modelpaymentTerm!=null)
                {
                    $body="<h3>Historial de termino Pago Cliente</h3>
                           <table border='1' class='paymentTermnTable'>
                            <tr>
                                <td> Termino Pago Cliente </td>
                                <td> Fecha de Inicio </td>
                                <td> Fecha Fin </td>
                                <td> Acciones </td>
                            </tr>";
                    foreach ($modelpaymentTerm as $key => $value)
                    { 
                        $body.= "<tr class='vistaTemp' id='".$value->id_ctp."'>
                                <td id='Contrato[id_termino_pago]'>".$value->tp_name."</td>
                                <td id='Contrato[start_date]'>".$value->start_date."</td>
                                <td id='Contrato[end_date]'>".$value->end_date."</td>
                                <td><img class='edit' name='editTP' alt='editar' src='/images/icon_lapiz.png'><img name='deleteTP' alt='borrar' src='/images/icon_x.gif'></td>
                              </tr>";  
                    }

                     $body.="</table>";
                }else{
                    $body="<h2>No hay Historial de termino pago modificado como clientes</h2>";
                }
            }else{
                $body="<font style='color:rgba(111,204,187,1);'>Seleccione el termino pago como cliente</font>";
            }
            echo $body;
        }
        public function actionDeleteCTP($id)
	{
            $model=$this->loadModel($id);
            if($model->delete()){
                Log::registrarLog(LogAction::getId('Eliminar Historial TerminoPago'),NULL, $id);
                echo "Delete ".$id;
            }else{
                echo "Fail ;(";
            }
	}
}
