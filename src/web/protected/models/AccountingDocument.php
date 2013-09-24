<?php

/**
 * This is the model class for table "accounting_document".
 *
 * The followings are the available columns in table 'accounting_document':
 * @property integer $id
 * @property string $issue_date
 * @property string $from_date
 * @property string $to_date
 * @property string $received_date
 * @property string $sent_date
 * @property string $doc_number
 * @property double $minutes
 * @property double $amount
 * @property string $note
 * @property integer $id_type_accounting_document
 *
 * The followings are the available model relations:
 * @property TypeAccountingDocument $idTypeAccountingDocument
 */
class AccountingDocument extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounting_document';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_type_accounting_document', 'required'),
			array('id_type_accounting_document', 'numerical', 'integerOnly'=>true),
			array('minutes, amount', 'numerical'),
			array('doc_number', 'length', 'max'=>50),
			array('note', 'length', 'max'=>250),
			array('issue_date, from_date, to_date, received_date, sent_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, from_date, to_date, received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document', 'safe', 'on'=>'search'),
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
			'idTypeAccountingDocument' => array(self::BELONGS_TO, 'TypeAccountingDocument', 'id_type_accounting_document'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_date' => 'Issue Date',
			'from_date' => 'From Date',
			'to_date' => 'To Date',
			'received_date' => 'Received Date',
			'sent_date' => 'Sent Date',
			'doc_number' => 'Doc Number',
			'minutes' => 'Minutes',
			'amount' => 'Amount',
			'note' => 'Note',
			'id_type_accounting_document' => 'Id Type Accounting Document',
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
		$criteria->compare('issue_date',$this->issue_date,true);
		$criteria->compare('from_date',$this->from_date,true);
		$criteria->compare('to_date',$this->to_date,true);
		$criteria->compare('received_date',$this->received_date,true);
		$criteria->compare('sent_date',$this->sent_date,true);
		$criteria->compare('doc_number',$this->doc_number,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('id_type_accounting_document',$this->id_type_accounting_document);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountingDocument the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
