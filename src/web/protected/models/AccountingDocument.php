<?php

/**
 * This is the model class for table "accounting_document".
 *
 * The followings are the available columns in table 'accounting_document':
 * @property integer $id
 * @property string $issue_date
 * @property string $from_date
 * @property string $to_date
 * @property string $valid_received_date
 * @property string $sent_date
 * @property string $doc_number
 * @property double $minutes
 * @property double $amount
 * @property string $note
 * @property integer $id_type_accounting_document
 * @property integer $id_carrier
 * @property string $email_received_date
 * @property string $valid_received_hour
 * @property string $email_received_hour
 * @property integer $id_currency
 * @property integer $confirm
 * @property double $min_etx
 * @property double $min_carrier
 * @property double $rate_etx
 * @property double $rate_carrier
 * @property integer $id_accounting_document
 * @property integer $id_destination
 * @property integer $id_destination_supplier
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Currency $idCurrency
 * @property TypeAccountingDocument $idTypeAccountingDocument
 * @property AccountingDocument $idAccountingDocument
 * @property Destination $id_destination
 * @property DestinationSupplier $id_destination_supplier
 * @property AccountingDocument[] $accountingDocuments
 * @property AccountingDocumentTemp[] $accountingDocuments
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
        public $carrier_groups;
        public $amount_etx;
        public $amount_carrier;
        public $dispute;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_type_accounting_document', 'required'),
			array('id_type_accounting_document, id_carrier, id_currency, confirm, id_accounting_document, id_destination, id_destination_supplier, dispute', 'numerical', 'integerOnly'=>true),
			array('minutes, amount, min_etx, min_carrier, rate_etx, rate_carrier', 'numerical'),
			array('doc_number', 'length', 'max'=>50),
			array('note', 'length', 'max'=>250),
			array('issue_date, from_date, to_date, valid_received_date, sent_date, email_received_date, valid_received_hour, email_received_hour,dispute', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, from_date, to_date, valid_received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, email_received_date, valid_received_hour, email_received_hour, id_currency, confirm, min_etx, min_carrier, rate_etx, rate_carrier, id_accounting_document, id_destination, id_destination_supplier, dispute', 'safe', 'on'=>'search'),
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
			'idCurrency' => array(self::BELONGS_TO, 'Currency', 'id_currency'),
			'idTypeAccountingDocument' => array(self::BELONGS_TO, 'TypeAccountingDocument', 'id_type_accounting_document'),
			'idAccountingDocument' => array(self::BELONGS_TO, 'AccountingDocument', 'id_accounting_document'),
			'accountingDocuments' => array(self::HAS_MANY, 'AccountingDocument', 'id_accounting_document'),
                        'accountingDocumentTemps' => array(self::HAS_MANY, 'AccountingDocumentTemp', 'id_accounting_document'),
                        'idDestination' => array(self::BELONGS_TO, 'Destination', 'id_destination'),
                        'idDestinationSupplier' => array(self::BELONGS_TO, 'DestinationSupplier', 'id_destination_supplier'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_date' => 'Fecha de Emisión',
			'from_date' => 'Inicio Periodo a Facturar',
			'to_date' => 'Fin Periodo a Facturar',
			'valid_received_date' => 'Valid Received Date',
			'email_received_date' => 'Fecha de recepción de Email',
			'valid_received_hour' => 'Valid Received Hour',
			'email_received_hour' => 'Hora de recepción de Email',
			'sent_date' => 'Fecha de envio',
			'doc_number' => 'Número de documento',
			'minutes' => 'Minutos',
			'amount' => 'Monto',
			'note' => 'Nota',
			'id_type_accounting_document' => 'Tipo de documento contable',
			'id_carrier' => 'Carrier',
			'id_currency' => 'Moneda',
			'confirm' => 'Confirmada',
			'min_etx' => 'Min Etx',
			'min_carrier' => 'Min Carrier',
			'rate_etx' => 'Tarifa Etx',
			'rate_carrier' => 'Tarifa Carrier',
			'id_accounting_document' => 'Documento Relacionado',
                        'id_destination' => 'Destino Etelix',
			'id_destination_supplier' => 'Destino Proveedor',
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
		$criteria->compare('valid_received_date',$this->valid_received_date,true);
		$criteria->compare('sent_date',$this->sent_date,true);
		$criteria->compare('doc_number',$this->doc_number,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('id_type_accounting_document',$this->id_type_accounting_document);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('email_received_date',$this->email_received_date,true);
		$criteria->compare('valid_received_hour',$this->valid_received_hour,true);
		$criteria->compare('email_received_hour',$this->email_received_hour,true);
		$criteria->compare('id_currency',$this->id_currency);
		$criteria->compare('confirm',$this->confirm);
		$criteria->compare('min_etx',$this->min_etx);
		$criteria->compare('min_carrier',$this->min_carrier);
		$criteria->compare('rate_etx',$this->rate_etx);
		$criteria->compare('rate_carrier',$this->rate_carrier);
		$criteria->compare('id_accounting_document',$this->id_accounting_document);
                $criteria->compare('$id_destination',$this->id_destination);
		$criteria->compare('$id_destination_supplier',$this->id_destination_supplier);

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
                     
        public static function listaFacturasEnviadas()
	{
		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.email_received_date, d.valid_received_date, to_char(d.email_received_hour, 'HH24:MI') as email_received_hour, to_char(d.valid_received_hour, 'HH24:MI') as valid_received_hour, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, issue_date, from_date, to_date, email_received_date, valid_received_date, email_received_hour, valid_received_hour, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_log_action=44)and confirm = 0 and id_type_accounting_document = 1)d, type_accounting_document t, carrier c, currency e
			  WHERE t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY d.doc_number ASC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function getConfirmID($confirm){           
            return self::model()->find("confirm=:confirm", array(':confirm'=>$confirm))->id;
        }
        
        public static function getDocNum($id){           
            return self::model()->find("id=:id", array(':id'=>$id))->doc_number;
        }
        
        public static function getBuscaMoneda($id){           
            return self::model()->find("id=:id", array(':id'=>$id))->id_currency;
        }
        
        public static function getExist($model)
        { 
            switch ($model->id_type_accounting_document){
                case '1':
                    //return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:tipo and from_date=:from_date and to_date=:to_date",array(":idCarrier"=>$model->id_carrier,":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document,":from_date"=>$model->from_date,":to_date"=>$model->to_date)); 
                    return self::model()->find("doc_number=:doc_number and id_type_accounting_document=:tipo",array(":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document)); 
                    break;
                case '2':
                    return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:tipo and from_date=:from_date and to_date=:to_date",array(":idCarrier"=>$model->id_carrier,":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document,":from_date"=>$model->from_date,":to_date"=>$model->to_date));
                    break;
                case '3':
                    return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:tipo",array(":idCarrier"=>$model->id_carrier,":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document));
                    break;
                case '4':
                    return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:tipo",array(":idCarrier"=>$model->id_carrier,":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document));
                    break;
                case '5':
                case '6':
                    return null;
//                    return self::model()->findBySql("Select * from accounting_document_temp where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and id_destination={$model->id_destination}");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and id_destination=:destination",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=destination"=>$model->id_destination));
                    break;
                
                    //return self::model()->findBySql("Select * from accounting_document_temp where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and id_destination_supplier={$model->id_destination_supplier}");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and id_destination_supplier=:destination_supplier",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=destination_supplier"=>$model->id_destination_supplier)); 
                    //break;
                case '7':
                    return self::model()->findBySql("Select * from accounting_document where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and doc_number='$model->doc_number'");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and doc_number=:doc_number",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=doc_number"=>$model->doc_number));
                    break;
                case '8':
                    return self::model()->findBySql("Select * from accounting_document where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and doc_number='$model->doc_number'");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and doc_number=:doc_number",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=doc_number"=>$model->doc_number));
                    break;
            }
        } 
        
        public static function getId_deDoc($CarrierDisp,$desdeDisp,$hastaDisp,$tipoDoc)
        { 
            return CHtml::listData(AccountingDocument::model()->findAll("id_carrier=:idCarrier AND id_type_accounting_document=:tipoDoc AND from_date=:from_date AND to_date=:to_date",array(":idCarrier"=>$CarrierDisp,":from_date"=>$desdeDisp,":to_date"=>$hastaDisp,":tipoDoc"=>$tipoDoc)),'doc_number','id');
        } 
        public static function getDocNumCont($CarrierDisp,$desdeDisp,$hastaDisp,$tipoDoc)
        { 
            return CHtml::listData(AccountingDocument::model()->findAll("id_carrier=:idCarrier AND id_type_accounting_document=:tipoDoc AND from_date=:from_date AND to_date=:to_date",array(":idCarrier"=>$CarrierDisp,":from_date"=>$desdeDisp,":to_date"=>$hastaDisp,":tipoDoc"=>$tipoDoc)),'id','doc_number');
        } 
//        este no se usa
        public static function getAcc_DocID($doc_num, $carrier){           
            return self::model()->find("doc_number=:doc_number AND id_carrier=:carrier", array(':doc_number'=>$doc_num,':carrier'=>$carrier))->id;
        }
        
        public static function lista_Disp_NotaCRec($factura)
	{
		$sql="SELECT t.id, t.min_etx, t.min_carrier, t. rate_etx, t. rate_carrier, (t.min_carrier*t.rate_carrier) as amount_carrier,(t.min_etx*t.rate_etx) as amount_etx,t.amount as dispute, d.name AS id_destination, t.id_accounting_document
                      FROM accounting_document t, destination d
                      WHERE t.id_type_accounting_document = 5 AND t.id_accounting_document = {$factura} AND t.id_destination = d.id";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function lista_Disp_NotaCEnv($factura)
	{
		$sql="SELECT t.id, t.min_etx, t.min_carrier, t. rate_etx, t. rate_carrier, (t.min_carrier*t.rate_carrier) as amount_carrier,(t.min_etx*t.rate_etx) as amount_etx,t.amount as dispute, d.name AS id_destination_supplier, t.id_accounting_document
                      FROM accounting_document t, destination_supplier d
                      WHERE t.id_type_accounting_document = 6 AND t.id_accounting_document = {$factura} AND t.id_destination_supplier = d.id";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function UpdateProv($modelAD)
        {
                $modelProv = AccountingDocument::model()->find("id_carrier=:idCarrier and from_date=:from_date and to_date=:to_date",array(":idCarrier"=>$modelAD->id_carrier,":from_date"=>$modelAD->from_date,":to_date"=>$modelAD->to_date));        
                if($modelProv->id!=NULL) 
                $modelProv->confirm="1";
		if($modelProv->save())  return true;
		   else   return false;
        }

}
