<?php

/**
 * This is the model class for table "geographic_zone".
 *
 * The followings are the available columns in table 'geographic_zone':
 * @property integer $id
 * @property string $name_zona
 * @property string $color_zona

 *
 * The followings are the available model relations:
 * @property Destination[] $destinations
 * @property DestinationInt[] $destinationInts

 */
class GeographicZone extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $id_destination;
    public $acciones;
    
    /**
     *
     */
	public function tableName()
	{
		return 'geographic_zone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name_zona, color_zona', 'required'),
			array('name_zona, color_zona', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name_zona, color_zona', 'safe', 'on'=>'search'),
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

			'destinations' => array(self::HAS_MANY, 'Destination', 'id_geographic_zone'),
			'destinationInts' => array(self::HAS_MANY, 'DestinationInt', 'id_geographic_zone'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Zona Geografica',
			'name_zona' => 'Nombre Zona',
			'color_zona' => 'Color Zona',
			'acciones' => 'Acciones',

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
		$criteria->compare('name_zona',$this->name_zona,true);
		$criteria->compare('color_zona',$this->color_zona,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GeographicZone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 *
	 */
	public static function getName($GeographicZone)
	{
	    return self::model()->find("id=:id", array(':id'=>$GeographicZone))->name_zona;
        }

        /**
         *
         */
        public static function getListGeo()
        {
            return CHtml::listData(GeographicZone::model()->findAll(array('order'=>'name_zona')),'id','name_zona');
        } 

        /**
         * Retorna el id de una busqueda especifica
         * @access public
         * @param string $name nombre del tipo de zona geografica
         * @return int $id
         */
        public static function getId($name)
        {
            if($name!=null)
            {
                     return self::model()->find('name_zona=:name',array(':name'=>$name))->id;
            }
            else
            {
                    return false;
            }
        }
         /**
	 *
	 */
	public static function getColor($id)
	{
	    return self::model()->find("id=:id", array(':id'=>$id))->color_zona;
        }
}
