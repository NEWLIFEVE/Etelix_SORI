<?php

/**
 * This is the model class for table "destination_supplier".
 *
 * The followings are the available columns in table 'destination_supplier':
 * @property integer $id
 * @property string $name
 * @property integer $id_carrier
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property AccountingDocument[] accountingDocument
 * @property AccountingDocumentTemp[] accountingDocumentTemp
 */
class DestinationSupplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'destination_supplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, id_carrier', 'required'),
			array('id_carrier', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, id_carrier', 'safe', 'on'=>'search'),
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
                    	'accountingDocument' => array(self::HAS_MANY, 'AccountingDocument', 'id_destination_supplier'),
			'accountingDocumentTemp' => array(self::HAS_MANY, 'AccountingDocumentTemp', 'id_destination_supplier'),
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
			'id_carrier' => 'Id Carrier',
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
		$criteria->compare('id_carrier',$this->id_carrier);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DestinationSupplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public static function getName($id){           
            return self::model()->find("id=:id", array(':id'=>$id))->name;
        }
        
        	
        public static function getId($carrier,$nombre=null)
	{
		if($nombre != null)
		{
			$sql="SELECT id FROM destination_supplier WHERE name='{$nombre}'";
			//$model=self::model()->find('name=:nombre',array(':nombre'=>$nombre));
			$model=self::model()->findBySql($sql);
			if($model == null)
			{
				$model=new DestinationSupplier;
				$model->name=$nombre;
				$model->id_carrier=$carrier;
				
				if($model->save())
				{
                                    return $model->id;
				}
			}
			else
			{
				return $model->getAttribute('id');
			}
		}
	}

        public static function resolvedId($DestinoSupp_sel,$DestinoSupp_input,$idCarrier)
        {
            if (isset($DestinoSupp_input)&&$DestinoSupp_input!='') {                               
                $id = self::model()->getId($idCarrier, $DestinoSupp_input); 
           } else {             //guarda un nuevo destination_suplier y luego se extrae el id para almacenarlo en documentos contables temp (id_destination_supplier
                $id = $DestinoSupp_sel;
          }
            return $id;
        }
}
