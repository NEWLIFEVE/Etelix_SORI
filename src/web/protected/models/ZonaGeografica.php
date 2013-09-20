<?php

/**
 * This is the model class for table "zona_geografica".
 *
 * The followings are the available columns in table 'zona_geografica':
 * @property integer $id
 * @property string $name_zona
 * @property string $color_zona
 * @property integer $id_destination
 * @property integer $id_destination_int
 *
 * The followings are the available model relations:
 * @property Destination $idDestination
 * @property DestinationInt $idDestinationInt
 */
class ZonaGeografica extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zona_geografica';
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
			array('id_destination, id_destination_int', 'numerical', 'integerOnly'=>true),
			array('name_zona, color_zona', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name_zona, color_zona, id_destination, id_destination_int', 'safe', 'on'=>'search'),
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
                    	'ZonaGeografica' => array(self::HAS_MANY, 'ZonaGeografica', 'id'),
			'idDestination' => array(self::BELONGS_TO, 'Destination', 'id_destination'),
			'idDestinationInt' => array(self::BELONGS_TO, 'DestinationInt', 'id_destination_int'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Zona Geografica',
			'name_zona' => 'Name Zona',
			'color_zona' => 'Color Zona',
			'id_destination' => 'Id Destination',
			'id_destination_int' => 'Id Destination Int',
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
		$criteria->compare('id_destination',$this->id_destination);
		$criteria->compare('id_destination_int',$this->id_destination_int);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ZonaGeografica the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
