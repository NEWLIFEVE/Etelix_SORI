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
		
		if(!Yii::app()->user->isGuest)
		{
			$this->render('index');
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
      
        public static function controlAcceso() {
        //$idUsuario = Yii::app()->user->id;
        $tipoUsuario = Yii::app()->user->type;
        /* ADMINISTRADOR */
        if ($tipoUsuario == 1) {
            return array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            array('label'=>'Cargar Archivos Captura', 'url'=>array('/balance/upload')),
                            array('label'=>'Dist. Comercial', 'url'=>array('/carrierManagers/distComercial')),
                            array('label'=>'Condiciones Comerciales', 'url'=>array('/contrato/create')),
//                            array('label'=>'Destinos y Zonas Geográficas', 'url'=>array('/GeographicZone/create')),
//                            array('label'=>'Zonas Geográficas', 'url'=>array('/GeographicZone/CreateZoneColor')),
                            array('label'=>'Documentos Contables', 'url'=>array('/AccountingDocumentTemp/create')),
                            array('label'=>'Confirmar Facturas Enviadas', 'url'=>array('/AccountingDocument/create')),
                            array('label'=>'Admin. Grupos', 'url'=>array('/carrier/NewGroupCarrier')),
                            array('label'=>'Log', 'url'=>array('/log/admin')),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        );
        }
        /* NOC */
        if ($tipoUsuario == 2) {
            return array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            array('label'=>'Cargar Archivos Captura', 'url'=>array('/balance/upload')),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        );
        }
        /* OPERACIONES */
        if ($tipoUsuario == 3) {
            return array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            array('label'=>'Cargar Archivos Captura', 'url'=>array('/balance/upload')),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        );
        }
        /* FINANZAS */
        if ($tipoUsuario == 4) {
            return array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
//                            array('label'=>'Dist.Comercial', 'url'=>array('/carrierManagers/distComercial')),
//                            array('label'=>'Condiciones Comerciales', 'url'=>array('/contrato/create')),
                            array('label'=>'Documentos Contables', 'url'=>array('/AccountingDocumentTemp/create')),
                            array('label'=>'Confirmar Facturas Enviadas', 'url'=>array('/AccountingDocument/create')),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        );
        }
        /* RETAIL */
        if ($tipoUsuario == 5) {
            return array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            array('label'=>'Dist.Comercial', 'url'=>array('/carrierManagers/distComercial')),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        );
        }
        }
        
        
        public function actionKeepAlive()
        {
            echo 'OK';
            Yii::app()->end();
        }
}