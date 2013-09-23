<?php

/**
 * This is the model class for table "credit_limit".
 *
 * The followings are the available columns in table 'credit_limit':
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $id_contrato
 * @property double $amount
 *
 * The followings are the available model relations:
 * @property Contrato $idContrato
 */
class CreditLimit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_limit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_date, id_contrato, amount', 'required'),
			array('id_contrato', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, start_date, end_date, id_contrato, amount', 'safe', 'on'=>'search'),
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
			'idContrato' => array(self::BELONGS_TO, 'Contrato', 'id_contrato'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'id_contrato' => 'Id Contrato',
			'amount' => 'Amount',
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
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('id_contrato',$this->id_contrato);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditLimit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
                       
        public static function getCredito($contrato){           
            $model = self::model()->find("id_contrato=:contrato and end_date IS NULL ", array(':contrato'=>$contrato));
            if($model!=NULL){
                return $model->amount;
            }else{
                return '';
            }
        }
}
