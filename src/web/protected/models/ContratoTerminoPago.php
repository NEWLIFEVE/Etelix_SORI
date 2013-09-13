<?php

/**
 * This is the model class for table "contrato_termino_pago".
 *
 * The followings are the available columns in table 'contrato_termino_pago':
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $id_contrato
 * @property integer $id_termino_pago
 *
 * The followings are the available model relations:
 * @property Contrato $idContrato
 * @property TerminoPago $idTerminoPago
 */
class ContratoTerminoPago extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'contrato_termino_pago';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_date', 'required'),
			array('id_contrato, id_termino_pago', 'numerical', 'integerOnly'=>true),
			array('end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, start_date, end_date, id_contrato, id_termino_pago', 'safe', 'on'=>'search'),
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
			'idTerminoPago' => array(self::BELONGS_TO, 'TerminoPago', 'id_termino_pago'),
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
			'id_termino_pago' => 'Id Termino Pago',
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
		$criteria->compare('id_termino_pago',$this->id_termino_pago);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContratoTerminoPago the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getTpId($contrato){
            $model = self::model()->find("id_contrato=:contrato and end_date IS NULL", array(':contrato'=>$contrato));
            if ($model!=NULL){
                return $model->id_termino_pago;
            }else{
                return '';
            }
        }
       
}
