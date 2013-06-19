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
		ini_set('max_execution_time', 900);
		ini_set('memory_limit', '256M');
		Yii::import("ext.Excel.Spreadsheet_Excel_Reader");
		$ruta="C:/Users/Manuel Zambrano/proyectos/sori/src/web/uploads/ruta venta internal.xls";
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF-8');
		$data->read($ruta);
		error_reporting(E_ALL ^ E_NOTICE);
		$date_balance=Utility::formatDate($data->sheets[0]['cells'][1][3]);
		$fecha = date('Y-m-j');
		$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
		if($nuevafecha == $date_balance)
		{
			$exitos=0;
			$fallas=0;
			for($i = 1; $i < $data->sheets[0]['numRows']; $i++)
			{
				$model=new Balance;
 				$model->date_balance=$date_balance;
 				$model->type=1;
 				$total=true;
 				$nuevo=true;
 				if($i>=5)
 				{
 					for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
					{
						//Obtengo el id del destino
 						if($j==1)
 						{
 							$id_destination=Destination::getId($data->sheets[0]['cells'][$i][$j]);
 							if($data->sheets[0]['cells'][$i][$j]=='Total')
 							{
 								break;
 							}
 						}
 						elseif($j==2)
 						{
 							//obtengo el id del carrier
 							$id_carrier=Carrier::getId($data->sheets[0]['cells'][$i][$j]);
 							if($data->sheets[0]['cells'][$i][$j]=='Total')
 							{
 								$total=false;
 							}
 						}
 						elseif($j==3)
 						{
 							//minutos
 							$minutes=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==4)
 						{
 							//ACD
 							$acd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==5)
 						{
 							//ASR
							$asr=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==6)
 						{
 							//Margin %
							$margin_percentage=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==7)
 						{
 							//Margin per Min
 							$margin_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==8)
 						{
 							//Cost per Min
 							$cost_per_minute=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==9)
 						{
 							//Revenue per Min
 							$revenue_per_min=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==10)
 						{
 							//PDD
 							$pdd=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==11)
 						{
 							//Imcomplete Calls
 							$incomplete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==12)
 						{
 							//Imcomplete Calls Ner
 							$incomplete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==13)
 						{
 							//Complete Calls Ner
 							$complete_calls_ner=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==14)
 						{
 							//Complete Calls
 							$complete_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==15)
 						{
 							//Calls Attempts
 							$calls_attempts=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==16)
 						{
 							//Duration Real
 							$duration_real=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==17)
 						{
 							//Duration Cost
 							$duration_cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==18)
 						{
 							//NER02 Efficient
 							$ner02_efficient=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==19)
 						{
 							//NER02 Seizure
 							$ner02_seizure=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==20)
 						{
 							//PDDCalls
 							$pdd_calls=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==21)
 						{
 							//Revenue
 							$revenue=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==22)
 						{
 							//Cost
 							$cost=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						elseif($j==23)
 						{
 							//Margin
 							$margin=Utility::notNull($data->sheets[0]['cellsInfo'][$i][$j]['raw']);
 						}
 						else
 						{
 							if($total)
 							{
 								$cant=$model->count('date_balance=:fecha AND id_carrier=:carrier AND id_destination=:destino',array(':fecha'=>Utility::formatDate($date_balance), ':carrier'=>$id_carrier, ':destino'=>$id_destination));
 								if($cant>0)
 								{
 									$model=Balance::model()->find('date_balance=:fecha AND id_carrier=:carrier AND id_destination=:destino',array(':fecha'=>Utility::formatDate($date_balance), ':carrier'=>$id_carrier, ':destino'=>$id_destination));
 									$model->date_change=date("Y-m-d");
 									$model->minutes=$minutes;
 									$model->acd=$acd;
 									$model->asr=$asr;
 									$model->margin_percentage=$margin_percentage;
 									$model->margin_per_minute=$margin_per_minute;
 									$model->cost_per_minute=$cost_per_minute;
 									$model->revenue_per_min=$revenue_per_min;
 									$model->pdd=$pdd;
 									$model->incomplete_calls=$incomplete_calls;
 									$model->incomplete_calls_ner=$incomplete_calls_ner;
 									$model->complete_calls_ner=$complete_calls_ner;
 									$model->complete_calls=$complete_calls;
 									$model->calls_attempts=$calls_attempts;
 									$model->duration_real=$duration_real;
 									$model->duration_cost=$duration_cost;
 									$model->ner02_efficient=$ner02_efficient;
 									$model->ner02_seizure=$ner02_seizure;
 									$model->pdd_calls=$pdd_calls;
 									$model->revenue=$revenue;
 									$model->cost=$cost;
 									$model->margin=$margin;
 									$model->saveAttributes(array('date_change','minutes','acd','asr','margin_percentage','margin_per_minute','cost_per_minute','revenue_per_min','pdd','incomplete_calls','incomplete_calls_ner','complete_calls_ner','complete_calls','calls_attempts','duration_real','duration_cost','ner02_efficient','ner02_seizure','pdd_calls','revenue','cost','margin'));
 								}
 								else
 								{
 									$model->minutes=$minutes;
 									$model->acd=$acd;
 									$model->asr=$asr;
 									$model->margin_percentage=$margin_percentage;
 									$model->margin_per_minute=$margin_per_minute;
 									$model->cost_per_minute=$cost_per_minute;
 									$model->revenue_per_min=$revenue_per_min;
 									$model->pdd=$pdd;
 									$model->incomplete_calls=$incomplete_calls;
 									$model->incomplete_calls_ner=$incomplete_calls_ner;
 									$model->complete_calls_ner=$complete_calls_ner;
 									$model->complete_calls=$complete_calls;
 									$model->calls_attempts=$calls_attempts;
 									$model->duration_real=$duration_real;
 									$model->duration_cost=$duration_cost;
 									$model->ner02_efficient=$ner02_efficient;
 									$model->ner02_seizure=$ner02_seizure;
 									$model->pdd_calls=$pdd_calls;
 									$model->revenue=$revenue;
 									$model->cost=$cost;
 									$model->margin=$margin;
 									$model->id_carrier=$id_carrier;
 									$model->id_destination=$id_destination;
 									if($model->save())
 									{
 										$exitos=$exitos+1;
 										$model->unsetAttributes();
 									}
 									else
 									{
 										$fallas=$fallas+1;
 									}
	 							}
 							}
 							$texto="Numero de exitos: ".$exitos." y fallas: ".$fallas;
 						}
					}
 				}
			}
		}
		else
		{
			Yii::app()->user->setFlash('error', "La fecha del archivo no conincide.");
			$this->redirect('/site/subirarchivo');
		}
		$this->render('guardar',array('data'=>$texto));
	}
}