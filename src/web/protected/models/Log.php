<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $date
 * @property string $hour
 * @property integer $id_log_action
 * @property integer $id_users
 * @property string $description_date
 * @property string $id_esp
 *
 * The followings are the available model relations:
 * @property LogAction $idLogAction
 * @property Users $idUsers
 */
class Log extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, hour', 'required'),
			array('id_log_action, id_users', 'numerical', 'integerOnly'=>true),
			array('description_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, hour, id_log_action, id_users, description_date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idLogAction' => array(self::BELONGS_TO, 'LogAction', 'id_log_action'),
			'idUsers' => array(self::BELONGS_TO, 'Users', 'id_users'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Fecha',
			'hour' => 'Hora',
			'id_log_action' => 'Accion',
			'id_users' => 'Usuario',
			'id_esp' => 'Id Especial',
			'description_date' => 'Fecha RR',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('hour',$this->hour,true);
		$criteria->compare('id_log_action',$this->id_log_action);
		$criteria->compare('id_users',$this->id_users);
		$criteria->compare('id_esp',$this->id_esp);
		$criteria->compare('description_date',$this->description_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'sort'=>array('defaultOrder'=>'date DESC, hour DESC'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	* Encargada de registrar el evento pasado como id
	*/
	public static function registrarLog($id,$description_date=null,$id_esp=null)
	{
		$model=new self;
		$model->hour=date("H:i:s");
		$model->date=date("Y-m-d");
		$model->id_log_action=$id;
		$model->id_users=Yii::app()->user->id;
		$model->description_date=$description_date;
		$model->id_esp=$id_esp;
		try {
			$model->save();
		} catch (ErrorException $e) {
		// este bloque no se ejecuta, no coincide el tipo de excepción
			error_log('Ocurrió una excepcion al guardar en el log '.$e->getMessage());
		}
	}
	public static function updateDocLog($model,$id_espNew)
	{
		$model->id_esp=$id_espNew;
		if($model->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	* Funcion encargada de verificar si se realizo la subida de los cuatro archivos de diario
	* se le pasa el nombre del archivo y verifica si ya se cargo, se le pasa una fecha verifica que los cuatro archivos se hallan cargado
	*/
	public static function disabledDiario($valor)
	{
		if(stripos($valor,"-"))
		{
			$model=self::model()->count("date=:fecha AND id_log_action>=1 AND id_log_action<=4", array(':fecha'=>$valor));
			if($model>=4)
			{
				return "disabled";
			}
			else
			{
				return false;
			}
		}
		else
		{
			$model=self::model()->count("date=:fecha AND id_log_action=:action",array(':fecha'=>date("Y-m-d"),':action'=>LogAction::getId($valor)));
			if($model>=1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	/*
	* Funcion que devuelve true si la accion ya fue realizada
	*/
	public static function existe($id)
	{
		$model=self::model()->find('id_log_action=:id AND date=:fecha AND hour<=:hora', array(':id'=>$id, ':fecha'=>date("Y-m-d"), ':hora'=>date("H:i:s")));
		if($model!=null)
			return true;
		else
			return false;
	}

	/*
	* Funcion que retorna los ultimos rangos de fechas cargados en rerate
	*/
	public static function getRerate()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		$fechas=array();
		$retorna=array();
		$resultados=self::model()->findAll("date<=current_date and date>=current_date - interval '1 week' and description_date is not null order by description_date asc");
		if($resultados!=null)
		{
			foreach ($resultados as $key => $fecha)
			{
				$fechas[$fecha->description_date]=true;
			}
			foreach ($fechas as $key => $value)
			{
				$retorna[]=$key;
			}
			return $retorna[0]."/".$retorna[count($retorna)-1];
		}
		else
		{
			return false;
		}
	}
	/**
	* Funcion que se encarga de mostrar si un rerate ya esta listo
	*/
	public static function getListo()
	{
		$rerate=self::model()->find("date=current_date AND hour<=current_time AND description_date IS NOT NULL");
		if($rerate->id)
		{
			$completado=self::model()->find("date=current_date AND hour<=current_time AND id_log_action=57");
			if($completado->id)
			{
				return "completado";
			}
			else
			{
				return "procesando";
			}
		}
		else
		{
			return "no";
		}
	}

	/**
	 *
	 */
	public static function logDiario()
	{
		$cargados="<h3>ESTATUS CARGA</h3>
  <p>Archivos Cargados:</p><ul>";
  $nocargados="</ul>
  <p>Archivos Faltantes:</p>
  <ul>";
		if(self::existe(1))
		{
			if(self::existe(3))
			{
				$cargados.="<li id='definitivo' class='cargados' name='diario'>Ruta External Definitivo</li>";
			}
			else
			{
				$cargados.="<li id='preliminar' class='cargados' name='diario'>Ruta External Preliminar</li>";
				$nocargados.="<li class='nocargados' name='diario'>Ruta External Definitivo</li>";
			}
		}
		else
		{
			$nocargados.="<li class='nocargados'>Ruta External</li>";
		}
		if(self::existe(2))
		{
			if(self::existe(4))
			{
				$cargados.="<li id='definitivo' class='cargados' name='diario'>Ruta Internal Definitivo</li>";
			}
			else
			{
				$cargados.="<li id='preliminar' class='cargados' name='diario'>Ruta Internal Preliminar</li>";
				$nocargados.="<li class='nocargados' name='diario'>Ruta Internal Definitivo</li>";
			}
		}
		else
		{
			$nocargados.="<li class='nocargados'>Ruta Internal</li>";
		}
		
		$cargados.="</ul>";
		$nocargados.="</ul>";
		return $cargados.$nocargados;
	}
        

}
