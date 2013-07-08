<?php

/**
 * This is the model class for table "log_action".
 *
 * The followings are the available columns in table 'log_action':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Log[] $logs
 */
class LogAction extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log_action';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'logs' => array(self::HAS_MANY, 'Log', 'id_log_action'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LogAction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/*
	*Funcion que devuelve el id del log_action consultado
	*/
	public static function getId($nombre)
	{
		$model=self::model()->find('name=:nombre',array('nombre'=>$nombre));
		if($model!=null)
		{
			return $model->id;
		}
		else
		{
			return false;
		}
	}
	/**
	* Funcion que devuelve el id del log_action consultado pero por like
	*/
	public static function getLikeId($nombre)
	{
		$model=self::model()->find('name like :nombre',array(':nombre'=>$nombre));
		if($model!=null)
		{
			return $model->id;
		}
		else
		{
			return false;
		}
	}
	/**
	* Funcion que devuelve el nombre del id consultado
	*/
	public static function getName($id)
	{
		$model=self::model()->find('id=:id',array(':id'=>$id));
		if($model!=null)
		{
			return $model->name;
		}
		else
		{
			return false;
		}
	}
}
