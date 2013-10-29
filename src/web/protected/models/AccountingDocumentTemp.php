<?php

/**
 * This is the model class for table "accounting_document_temp".
 *
 * The followings are the available columns in table 'accounting_document_temp':
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
 *
 * The followings are the available model relations:
 * @property TypeAccountingDocument $idTypeAccountingDocument
 * @property Carrier $idCarrier
 */
class AccountingDocumentTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounting_document_temp';
	}
        
        public $carrier_groups;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_type_accounting_document', 'required'),
			array('id_type_accounting_document, id_carrier', 'numerical', 'integerOnly'=>true),
			array('minutes, amount', 'numerical'),
			array('doc_number', 'length', 'max'=>50),
			array('note', 'length', 'max'=>250),

			array('issue_date, from_date, to_date,  valid_received_date, email_received_date, valid_received_hour, email_received_hour, sent_date, id_currency, confirm', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, from_date, to_date,  valid_received_date, email_received_date, valid_received_hour, email_received_hour, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency, confirm', 'safe', 'on'=>'search'),
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
                        'idCarrier' => array(self::BELONGS_TO, 'Carrier', 'id_carrier'),
                        'idCurrency' => array(self::BELONGS_TO, 'Currency', 'id_currency'),
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
			'confirm' => 'Confirmar',
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
		$criteria->compare('email_received_date',$this->email_received_date,true);
		$criteria->compare('valid_received_hour',$this->valid_received_hour,true);
		$criteria->compare('email_received_hour',$this->email_received_hour,true);
		$criteria->compare('sent_date',$this->sent_date,true);
		$criteria->compare('doc_number',$this->doc_number,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('id_type_accounting_document',$this->id_type_accounting_document);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_currency',$this->id_currency);
		$criteria->compare('confirm',$this->confirm);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountingDocumentTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

       
//        falta.....................
        public static function getListDocTemp()
        {
            return CHtml::listData(AccountingDocumentTemp::model()->findAll(), '*');
            
            $id = self::getId('Unknown_Carrier');
            return CHtml::listData(Carrier::model()->findAll("id !=:id order by name ASC",array(":id"=>$id)), 'id', 'name'); 
        }
        /*
        * con el id de documento me trae el tipo del documento
        */
        public static function getTypeDoc($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->id_type_accounting_document;
        }

        /**
	 * @access public
	 * @param $usuario id del usuario en session
	 */
//	public static function listaGuardados($usuario)//regerencia
//	{
//		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.email_received_date, d.valid_received_date, to_char(d.email_received_hour, 'HH24:MI') as email_received_hour, to_char(d.valid_received_hour, 'HH24:MI') as valid_received_hour, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
//			  FROM(SELECT id, issue_date, from_date, to_date, email_received_date, valid_received_date, email_received_hour, valid_received_hour, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
//			  	   FROM accounting_document_temp
//			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e
//			  WHERE t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY id DESC";
//		$model=self::model()->findAllBySql($sql);
//
//		return $model;
//	}
        
	public static function listaFacRecibidas($usuario)
	{
		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.email_received_date, d.valid_received_date, to_char(d.email_received_hour, 'HH24:MI') as email_received_hour, to_char(d.valid_received_hour, 'HH24:MI') as valid_received_hour, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, issue_date, from_date, to_date, email_received_date, valid_received_date, email_received_hour, valid_received_hour, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e
			  WHERE d.id_type_accounting_document=2 AND  t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        
        public static function listaFacturasEnviadas($usuario)
	{
		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, issue_date, from_date, to_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e
			  WHERE d.id_type_accounting_document=1 AND t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        
        public static function listaPagos($usuario)
	{
		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, issue_date, from_date, to_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e
			  WHERE d.id_type_accounting_document=3 AND t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function listaCobros($usuario)
	{
		$sql="SELECT d.id,  d.valid_received_date, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, c.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, valid_received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e
			  WHERE d.id_type_accounting_document=4 AND t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        
        
        public static function getExist($idCarrier, $numDocumento, $selecTipoDoc,$desdeFecha,$hastaFecha)
        { 
            return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:id_type_accounting_document and from_date=:from_date and to_date=:to_date",array(":idCarrier"=>$idCarrier,":doc_number"=>$numDocumento,":id_type_accounting_document"=>$selecTipoDoc,":from_date"=>$desdeFecha,":to_date"=>$hastaFecha));
        } 
        
        public static function getValidDate($EmailfechaRecepcion,$dia)
        {    
            switch ($dia) {
                
                case 1:
                      return date('Y-m-d', strtotime('+1 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 2:
                      return date('Y-m-d', strtotime('+6 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 3:
                      return date('Y-m-d', strtotime('+5 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 4:
                      return date('Y-m-d', strtotime('+4 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 5:
                      return date('Y-m-d', strtotime('+3 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 6:
                      return date('Y-m-d', strtotime('+2 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 7:
                      return date('Y-m-d', strtotime('+1 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
             }                                                             
        }
        
//        public function getDates($EmailfechaRecepcion,$EmailHoraRecepcion){
//            $fecha = strtotime($EmailfechaRecepcion);
//            $dia = date("N", $fecha);
//            $Dates = array();
//                if ($dia == 1 || $dia == 2) {
//                    if ($EmailHoraRecepcion >= '08:00 AM' && $EmailHoraRecepcion <= '05:00 PM') {
//                        $Dates['validDate'] = $EmailfechaRecepcion;
//                        $Dates['validHour'] = $EmailHoraRecepcion;
//                        $Dates['emailDate'] = $EmailfechaRecepcion;
//                        $Dates['emailHour'] = $EmailHoraRecepcion;
//                      
//                    } else {
//                        if($EmailHoraRecepcion < '08:00 AM'){
//                            $Dates['validDate'] = $EmailfechaRecepcion;
//                        }else{
//                            $Dates['validDate'] = self::getValidDate($EmailfechaRecepcion, $dia);
//                        }
//                        $Dates['validHour'] = '08:00 AM';
//                        $Dates['emailDate'] = $EmailfechaRecepcion;
//                        $Dates['emailHour'] = $EmailHoraRecepcion;
//                    }
//                } else {
//                    $Dates['validDate'] = self::getValidDate($EmailfechaRecepcion, $dia);
//                    $Dates['validHour'] = '08:00 AM';
//                    $Dates['emailDate'] = $EmailfechaRecepcion;
//                    $Dates['emailHour'] = $EmailHoraRecepcion;
//                }
//                return $Dates;
//        }
}