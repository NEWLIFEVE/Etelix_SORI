<?php

/**
 * This is the model class for table "contrato".
 *
 * The followings are the available columns in table 'contrato':
 * @property integer $id
 * @property string $sign_date
 * @property string $production_date
 * @property string $end_date
 * @property integer $id_carrier
 * @property integer $id_company
 * @property integer $up
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Company $idCompania
 * @property DaysDisputeHistory[] $daysDisputeHistories
 * @property ContratoMonetizable[] $contratoMonetizables
 * @property ContratoTerminoPago[] $contratoTerminoPagos
 * @property ContratoLimites[] $contratoLimites
 */
class Contrato extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
    public $id_termino_pago;
    public $id_monetizable;
    public $id_limite_credito;
    public $id_limite_compra;
    public $id_managers;
    public $id_disputa;
    public $id_disputa_solved;
    public $status;
	public function tableName()
	{
		return 'contrato';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_carrier, id_company', 'required'),
			array('id_carrier, id_company', 'numerical', 'integerOnly'=>true),
			array('production_date, end_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sign_date, production_date, end_date, id_carrier, id_company, up', 'safe', 'on'=>'search'),
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
			'idCompania' => array(self::BELONGS_TO, 'Company', 'id_company'),
			'daysDisputeHistories' => array(self::HAS_MANY, 'DaysDisputeHistory', 'id_contrato'),
			'contratoMonetizables' => array(self::HAS_MANY, 'ContratoMonetizable', 'id_contrato'),
			'contratoTerminoPagos' => array(self::HAS_MANY, 'ContratoTerminoPago', 'id_contrato'),
			'contratoLimites' => array(self::HAS_MANY, 'ContratoLimites', 'id_contrato'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sign_date' => 'Fecha Firma de Contrato',
			'production_date' => 'Fecha puesta en Produccion',
			'end_date' => 'Fecha fin de Contrato',
			'id_carrier' => 'Carriers',
			'id_company' => 'Compania',
			'id_termino_pago' => 'Termino de Pago',
			'id_monetizable' => 'Monetizable',
			'id_limite_credito' => 'Limite de Credito',
			'id_limite_compra' => 'Limite de Compra',
                        'id_managers' => 'Account Manager',
                        'id_disputa' => 'Dias max para disputar',
                        'id_disputa_solved' => 'Dias para solventar disputas',
                        'up' => 'Unidad de Produccion',
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
		$criteria->compare('sign_date',$this->sign_date,true);
		$criteria->compare('production_date',$this->production_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_company',$this->id_company);
		$criteria->compare('up',$this->up);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Contrato the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getListRevisaFechaFirma($idCarrier)
	{
            $sql="Select c.id, c.sign_date
                  From contrato c, carrier x
                  Where x.id =$idCarrier and x.id = c.id_carrier";
            
            return CHtml::listData(Contrato::model()->findBySql($sql),'id','sing_date');
 
	}
        
        public static function DatosContrato($carrier)
        {
           return self::model()->find("id_carrier=:carrier and end_date IS NULL", array(':carrier'=>$carrier));       
        }   
        public static function getUP($id){           
            return self::model()->find("id_carrier=:id_carrier", array(':id_carrier'=>$id))->up;
        }
}
