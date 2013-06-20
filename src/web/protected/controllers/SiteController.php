<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if(!Yii::app()->user->isGuest)
		{
			$this->render('upload');
		}
		else
		{
			$model=new LoginForm;
			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
					$this->redirect(Yii::app()->user->returnUrl);
			}
			// display the login form
			$this->render('login',array('model'=>$model));	
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	/*public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}*/

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionUpload()
	{
		Yii::import("ext.EAjaxUpload.qqFileUploader");
 
        $folder='uploads/';// folder for uploaded files
        $allowedExtensions = array("xls", "xlsx");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = 10 * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($folder);
        $return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
 
        $fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
        $fileName=$result['filename'];//GETTING FILE NAME
 
        echo $return;// it's array
	}
	public function actionSubirarchivo()
	{
		$this->render('upload');
	}
	public function actionGuardar()
	{
		$diarios= array(1=>'VentaInternal', 2=>'VentaExternal', 3=>'CompraInternal', 4=>'CompraExternal');
		$horarios= array(1=>'ruta venta internal hora', 2=>'ruta venta hora', 3=>'ruta compra internal hora', 4=>'ruta compra hora');
		$rerates= array(1=>'ruta venta internal rr', 2=>'ruta venta rr', 3=>'ruta compra internal rr', 4=>'ruta compra rr');
		$todos=array(1=>'VentaInternal', 2=>'VentaExternal', 3=>'CompraInternal', 4=>'CompraExternal',5=>'ruta venta internal hora', 6=>'ruta venta hora', 7=>'ruta compra internal hora', 8=>'ruta compra hora',9=>'ruta venta internal rr', 10=>'ruta venta rr', 11=>'ruta compra internal rr', 12=>'ruta compra rr');
		$texto="Archivos: ";
		if(isset($_POST['tipo']))
		{
			if($_POST['tipo']=="dia")
			{
				foreach($diarios as $key => $diario)
				{
					$tipo=Reader::tipo($diario);
					$vencom=Reader::vencom($diario);
					$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".xls";
					$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$diario.".XLS";
					if(file_exists($minuscula))
					{
						$resultado=Reader::diario($minuscula,$vencom,$tipo);
						if($resultado!=false)
						{
							$this->render('guardar',array('data'=>$resultado));
						}
					}
					elseif(file_exists($mayuscula))
					{
						$resultado=Reader::diario($mayuscula,$vencom,$tipo);
						if($resultado!=false)
						{
							$this->render('guardar',array('data'=>$resultado));
						}
					}
				}
				$variable="diarios";
			}
			elseif($_POST['tipo']=="hora")
			{
				foreach($horarios as $key => $hora)
				{
					$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$hora.".xls";
					$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$hora.".XLS";
					if(file_exists($minuscula))
					{
						$texto.=$hora." existe, ";
					}
					elseif(file_exists($mayuscula))
					{
						$texto.=$hora." existe, ";
					}
					else
					{
						$texto.=$hora." no existe, ";
					}
				}
				$variable="horarios";
			}
			elseif($_POST['tipo']=="rerate")
			{
				foreach($rerates as $key => $rerate)
				{
					$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".xls";
					$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$rerate.".XLS";
					if(file_exists($minuscula))
					{
						$texto.=$rerate." existe, ";
					}
					elseif(file_exists($mayuscula))
					{
						$texto.=$rerate." existe, ";
					}
					else
					{
						$texto.=$rerate." no existe, ";
					}
				}
				$variable="rerates";
			}
		}
		else
		{
			foreach($todos as $key => $var)
			{
				$minuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$var.".xls";
				$mayuscula = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$var.".XLS";
				if(file_exists($minuscula))
				{
					unlink($minuscula);
				}
				if(file_exists($mayuscula))
				{
					unlink($mayuscula);
				}
			}
			Yii::app()->user->setFlash('error', "Debe escoger una opción.");
			$this->redirect('/site/subirarchivo');
		}
	}
}