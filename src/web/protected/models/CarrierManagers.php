<?php

/**
 * This is the model class for table "carrier_managers".
 *
 * The followings are the available columns in table 'carrier_managers':
 * @property string $start_date
 * @property string $end_date
 * @property integer $id_carrier
 * @property integer $id_users
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Users $idUsers
 */
class CarrierManagers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'carrier_managers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_carrier, id_users', 'numerical', 'integerOnly'=>true),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('start_date, end_date, id_carrier, id_users', 'safe', 'on'=>'search'),
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
			'idCarrier' => array(self::BELONGS_TO, 'Carrier', 'id_carrier'),
			'idUsers' => array(self::BELONGS_TO, 'Users', 'id_users'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'id_carrier' => 'Id Carrier',
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

		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_users',$this->id_users);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CarrierManagers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
