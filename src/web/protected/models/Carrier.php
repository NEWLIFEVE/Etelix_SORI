<?php

/**
 * This is the model class for table "carrier".
 *
 * The followings are the available columns in table 'carrier':
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $record_date
 * @property integer $id_carrier_groups
 * @property integer $group_leader
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Balance[] $balances
 * @property CarrierManagers[] $carrierManagers
 * @property AccountingDocumentTemp[] $accountingDocumentTemps
 * @property AccountingDocument[] $accountingDocuments
 */
class Carrier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'carrier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, record_date', 'required', 'on'=>'create'),
			array('name', 'length', 'max'=>50),
			array('address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, address, record_date, id_carrier_groups, group_leader, status', 'safe', 'on'=>'search'),
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
                        'carrierGroups' => array(self::BELONGS_TO, 'CarrierGroups', 'id_carrier_groups'),
			'balances'=>array(self::HAS_MANY,'Balance','id_carrier_supplier'),
			'balances1'=>array(self::HAS_MANY,'Balance','id_carrier_customer'),
			'carrierManagers' => array(self::HAS_MANY, 'CarrierManagers', 'id_carrier'),
			'accountingDocumentTemps' => array(self::HAS_MANY, 'AccountingDocument', 'id_carrier'),
			'accountingDocuments' => array(self::HAS_MANY, 'AccountingDocumentTemp', 'id_carrier'),
                        'destinationSupplier' => array(self::HAS_MANY, 'DestinationSupplier', 'id_carrier'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Grupos',
			'name' => 'Nombre',
			'address' => 'Direccion',
			'record_date' => 'Fecha Registro',
			'id_carrier_groups' => 'Grupo',
			'group_leader' => 'Principal',
			'status' => 'Status',
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('record_date',$this->record_date,true);
		$criteria->compare('id_carrier_groups',$this->id_carrier_groups,true);
		$criteria->compare('group_leader',$this->group_leader,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'name ASC'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Carrier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getId($nombre=null)
	{
		if($nombre != null && $nombre != 'Total')
		{
			$sql="SELECT id FROM carrier WHERE name='{$nombre}'";
			//$model=self::model()->find('name=:nombre',array(':nombre'=>$nombre));
			$model=self::model()->findBySql($sql);
			if($model == null)
			{
				$model=new Carrier;
				$model->name=$nombre;
				$model->record_date=date("Y-m-d");
				if($model->save())
				{
                                    $modelCM=new CarrierManagers;
                                    $modelCM->id_carrier=$model->id;
                                    $modelCM->id_managers=8;
                                    $modelCM->start_date=date('Y-m-d');
                                    $modelCM->save();
                                    return $model->id;
				}
			}
			else
			{
				return $model->getAttribute('id');
			}
		}
	}
        
        public static function getName($id){           
            return self::model()->find("id=:id", array(':id'=>$id))->name;
        }
        
        public static function getListCarrier()
        {
            return CHtml::listData(Carrier::model()->findAll(array('order' => 'name')), 'id', 'name');
        }
        public static function getListCarriersSinGrupo()
        {
            return CHtml::listData(Carrier::model()->findAll("id_carrier_groups is null order by name ASC"), 'id', 'name');
        }
        public static function getListCarriersGrupo($id_grupo)
        {
            return CHtml::listData(Carrier::model()->findAll("id_carrier_groups =:grupo order by name ASC",array(":grupo"=>$id_grupo)), 'id', 'name');
        }
        public static function getListCarrierNoUNKNOWN()
        {
            $id = self::getId('Unknown_Carrier');
            return CHtml::listData(Carrier::model()->findAll("id !=:id order by name ASC",array(":id"=>$id)), 'id', 'name');
        } 

        public static function getID_G($id_grupo){           
            return self::model()->find("id_carrier_groups=:id_carrier_groups", array(':id_carrier_groups'=>$id_grupo))->id;
        }
                
        public static function getSerchOne($idGrupo)
        {
            return self::model()->find("id_carrier_groups =:idGrupo and group_leader = 1",array(":idGrupo"=>$idGrupo));
        } 
        public static function getCarrierLeader($idGrupo)
        {
            return self::model()->find("id_carrier_groups =:idGrupo and group_leader = 1",array(":idGrupo"=>$idGrupo))->id;
        } 
        public static function getStatus($id)
        {
            return self::model()->find("id =:id",array(":id"=>$id))->status;
        } 
}