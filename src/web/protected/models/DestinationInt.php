<?php

/**
 * This is the model class for table "destination_int".
 *
 * The followings are the available columns in table 'destination_int':
 * @property integer $id
 * @property string $name
 * @property integer $id_geographic_zone
 *
 * The followings are the available model relations:
 * @property GeographicZone $idGeographicZone
 * @property Balance[] $balances
 */
class DestinationInt extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'destination_int';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
                        array('id_geographic_zone', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name,id_geographic_zone', 'safe', 'on'=>'search'),
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
			'balances' => array(self::HAS_MANY, 'Balance', 'id_destination_int'),
			'idGeographicZone' => array(self::BELONGS_TO, 'GeographicZone', 'id_geographic_zone'),
	
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
                        'id_geographic_zone' => 'Id Geographic Zone',
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
                $criteria->compare('id_geographic_zone',$this->id_geographic_zone);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DestinationInt the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getId($nombre=null)
	{
		if($nombre != null)
		{
			$model=self::model()->find('name=:nombre',array(':nombre'=>$nombre));
			if($model == null)
			{
				$model=new self;
				$model->name=$nombre;
				$model->id_geographic_zone=GeographicZone::getId('Sin Asignar');
				if($model->save())
				{
					return $model->id;
				}
			}
			else
			{
				return $model->id;
			}
		}
	}
        
 public static function getListDestinationIntNoAsig()
	{          
            return CHtml::listData(DestinationInt::model()->findAll("id_geographic_zone=:zona order by name asc",array(':zona'=>2)),'id','name');
	}    
        
                /**
	 * este busca los destinos asignados a zonas geograficas
         * a partir del valor recibido en el controlador de zona geografica
	 */
        public static function getListDestinationIntAsignados($id)
	{
            return CHtml::listData(DestinationInt::model()->findAll('id_geographic_zone=:zona order by name asc',array(':zona'=>$id)),'id','name');
	
	}
}
