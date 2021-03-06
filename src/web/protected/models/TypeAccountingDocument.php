<?php

/**
 * This is the model class for table "type_accounting_document".
 *
 * The followings are the available columns in table 'type_accounting_document':
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property AccountingDocumentTemp[] $accountingDocumentTemps
 * @property AccountingDocument[] $accountingDocuments
 */
class TypeAccountingDocument extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'type_accounting_document';
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
			array('name', 'length', 'max'=>50),
			array('description', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description', 'safe', 'on'=>'search'),
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
			'accountingDocumentTemps' => array(self::HAS_MANY, 'AccountingDocumentTemp', 'id_type_accounting_document'),
			'accountingDocuments' => array(self::HAS_MANY, 'AccountingDocument', 'id_type_accounting_document'),
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
			'description' => 'Description',
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
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TypeAccountingDocument the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);

	}

	/**
	 * @access public
	 */
	public static function getListTypeAccountingDocument()
    {
        return CHtml::listData(TypeAccountingDocument::model()->findAllBySql("select id, name from type_accounting_document where id not in (9,10,11,12,13,14,15)"), 'id', 'name');
    }

    /**
     * @access public
     */
    public static function getName($id)
    {
        return self::model()->find("id=:id", array(':id'=>$id))->name;
    }

    /**
     *
     */
    public static function getId($name)
    {
    	return self::model()->find("name LIKE :name", array(':name'=>$name))->id;
    }
}
