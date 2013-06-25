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
 *
 * The followings are the available model relations:
 * @property LogAction $idLogAction
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
			array('id, date, hour, id_log_action, id_users', 'required'),
			array('id, id_log_action, id_users', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, hour, id_log_action, id_users', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'hour' => 'Hour',
			'id_log_action' => 'Id Log Action',
			'id_users' => 'Id Users',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
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

	public static function registrarLog($id)
	{
        $model=new self;
        $model->id_users=Yii::app()->user->id;
        $model->id_log_action=$id;
        $model->date=date("Y-m-d ");
        $model->hour=date("H:i:s");
        $model->save();
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
}
