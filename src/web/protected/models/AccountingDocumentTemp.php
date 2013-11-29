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
 * @property double $min_etx
 * @property double $min_carrier
 * @property double $rate_etx
 * @property double $rate_carrier
 * @property integer $id_accounting_document
 * @property integer $id_destination
 * @property integer $id_destination_supplier
 * 
 * The followings are the available model relations:
 * @property TypeAccountingDocument $idTypeAccountingDocument
 * @property Carrier $idCarrier
 * @property AccountingDocument $idAccountingDocument
 * @property Destination $id_destination
 * @property DestinationSupplier $id_destination_supplier
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
        public $amount_etx;
        public $amount_carrier;
        public $dispute;
        public $select_dest_supplier;
        public $input_dest_supplier;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('id_type_accounting_document', 'required'),
			array('id_type_accounting_document, id_carrier, id_currency, confirm, id_accounting_document, id_destination, id_destination_supplier', 'numerical', 'integerOnly'=>true),
			array('minutes, amount, min_etx, min_carrier, rate_etx, rate_carrier,select_dest_supplier,carrier_groups', 'numerical'),
			array('doc_number,input_dest_supplier', 'length', 'max'=>50),
			array('note', 'length', 'max'=>250),
			array('issue_date, from_date, to_date, valid_received_date, sent_date, email_received_date, valid_received_hour, email_received_hour', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, from_date, to_date, valid_received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, email_received_date, valid_received_hour, email_received_hour, id_currency, confirm, min_etx, min_carrier, rate_etx, rate_carrier, id_accounting_document, id_destination, id_destination_supplier', 'safe', 'on'=>'search'),
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
                        'idAccountingDocument' => array(self::BELONGS_TO, 'AccountingDocument', 'id_accounting_document'),
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
			'valid_received_date' => 'Fecha de recepción',
			'email_received_date' => 'Fecha de recepción de Email',
			'valid_received_hour' => 'Valid Received Hour',
			'email_received_hour' => 'Hora de recepción de Email',
			'sent_date' => 'Fecha de envio',
			'doc_number' => 'Número de Documento',
			'minutes' => 'Minutos',
			'amount' => 'Monto',
			'note' => 'Nota',
			'id_type_accounting_document' => 'Tipo de documento contable',
			'id_carrier' => 'Carrier',
			'id_currency' => 'Moneda',
			'confirm' => 'Confirmar',
                        'min_etx' => 'Minutos Etelix',
			'min_carrier' => 'Minutos Proveedor',
			'rate_etx' => 'Tarifa Etelix',
			'rate_carrier' => 'Tarifa Proveedor',
			'id_accounting_document' => 'Número de Factura',
                        'carrier_groups'=>'Grupo',
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
        /*
        * con el id de documento me trae el id de carrier
        */
        public static function getIDcarrier($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->id_carrier;
        }
        /*
        * con el id de documento me trae el id de disputa
        */
        public static function getId_disputa($idCarrier, $DesdeFecha, $HastaFecha, $idAccDocument)
        {
           return self::model()->find("id_carrier =:idCarrier and from_date =:DesdeFecha and to_date =:HastaFecha and id_accounting_document =:idAccDocument and id_type_accounting_document= 5",array(":idCarrier"=>$idCarrier,":DesdeFecha"=>$DesdeFecha,":HastaFecha"=>$HastaFecha,":idAccDocument"=>$idAccDocument))->id;
        }
        /*
        * con el id de documento me trae el id destination
        */
        public static function getId_dest($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id,":id"=>$id,":id"=>$id))->id_destination;
        }
        /*
        * con el id de documento me trae  min_etx
        */
        public static function getMinEtx($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->min_etx;
        }
        /*
        * con el id de documento me trae  min_carrier
        */
        public static function getMinProv($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->min_carrier;
        }
        /*
        * con el id de documento me trae rate_etx
        */
        public static function getMontoEtx($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->rate_etx;
        }
        /*
        * con el id de documento me trae rate_etx
        */
        public static function getMontoProv($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->rate_carrier;
        }

        /**
	 * @access public
	 * @param $usuario id del usuario en session
	 */     
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
            $idAction = LogAction::getLikeId('Crear Pago Temp');
		$sql="SELECT d.id, d.issue_date, d.from_date, d.to_date, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, g.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, issue_date, from_date, to_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e, carrier_groups g
			  WHERE d.id_type_accounting_document=3 AND t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency AND c.id_carrier_groups=g.id ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function listaCobros($usuario)
	{
            $idAction = LogAction::getLikeId('Crear Cobro Temp');
		$sql="SELECT d.id,  d.valid_received_date, d.sent_date, d.doc_number, d.minutes, d.amount, d.note, t.name AS id_type_accounting_document, g.name AS id_carrier, e.name AS id_currency
			  FROM(SELECT id, valid_received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, id_currency
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, carrier c, currency e, carrier_groups g
			  WHERE d.id_type_accounting_document=4 AND t.id = d.id_type_accounting_document AND c.id=d.id_carrier AND e.id=d.id_currency AND c.id_carrier_groups=g.id ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function lista_DispRec($usuario)
	{
            $idAction = LogAction::getLikeId('Crear Disputa Recibida Temp');
		$sql="SELECT d.id,  d.from_date, d.to_date, d.min_etx, d.min_carrier, d.rate_etx, d.rate_carrier,(d.min_carrier*d.rate_carrier) as amount_carrier,(d.min_etx*d.rate_etx) as amount_etx,d.amount as dispute, e.name AS id_destination, t.name AS id_type_accounting_document,  f.doc_number AS id_accounting_document, c.name AS id_carrier
			  FROM(SELECT id, from_date, to_date, id_accounting_document, min_etx, min_carrier, rate_etx, rate_carrier, amount, id_destination, id_type_accounting_document, id_carrier
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, accounting_document f, carrier c, destination e
			  WHERE d.id_type_accounting_document=5 AND t.id = d.id_type_accounting_document AND f.id = d.id_accounting_document AND e.id = d.id_destination AND c.id=d.id_carrier ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function lista_DispEnv($usuario)
	{
            $idAction = LogAction::getLikeId('Crear Disputa Enviada Temp');
            
		$sql="SELECT d.id,  d.from_date, d.to_date, d.min_etx, d.min_carrier, d.rate_etx, d.rate_carrier,(d.min_carrier*d.rate_carrier) as amount_carrier,(d.min_etx*d.rate_etx) as amount_etx, d.amount as dispute, e.name AS id_destination_supplier, t.name AS id_type_accounting_document,  f.doc_number AS id_accounting_document, c.name AS id_carrier
			  FROM(SELECT id, from_date, to_date, id_accounting_document, min_etx, min_carrier, rate_etx, rate_carrier, amount, id_destination_supplier, id_type_accounting_document, id_carrier
			  	   FROM accounting_document_temp
			  	   WHERE id IN (SELECT id_esp FROM log WHERE id_users={$usuario} AND id_log_action=43))d, type_accounting_document t, accounting_document f, carrier c, destination_supplier e
			  WHERE d.id_type_accounting_document=6 AND t.id = d.id_type_accounting_document AND f.id = d.id_accounting_document AND e.id = d.id_destination_supplier AND c.id=d.id_carrier ORDER BY id DESC";
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function lista_NotCredEnv($usuario)
	{
            $idAction = LogAction::getLikeId('Crear Disputa Enviada Temp');
            
		$sql="select a.id, a.issue_date, a.doc_number, a.id_accounting_document, a.amount, c.name AS id_carrier, f.doc_number AS id_accounting_document
                from accounting_document_temp a, carrier c, accounting_document f 
                where a.id_type_accounting_document = 7 and c.id=a.id_carrier AND f.id=a.id_accounting_document ORDER BY id DESC";//esto es provisional, faltan datos en la consulta
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        public static function lista_NotCredRec($usuario)
	{
            $idAction = LogAction::getLikeId('Crear Disputa Enviada Temp');
            
		$sql="select a.id, a.issue_date, a.doc_number, a.id_accounting_document, a.amount, c.name AS id_carrier, f.doc_number AS id_accounting_document
                from accounting_document_temp a, carrier c, accounting_document f 
                where a.id_type_accounting_document = 8 and c.id=a.id_carrier AND f.id=a.id_accounting_document ORDER BY id DESC";//esto es provisional, faltan datos en la consulta
		$model=self::model()->findAllBySql($sql);

		return $model;
	}
        /**
         * comprueba que no existan facturas en a_d_temp....
         * @param type $idCarrier
         * @param type $numDocumento
         * @param type $selecTipoDoc
         * @param type $desdeFecha
         * @param type $hastaFecha
         * @return type
         */
        
        public static function getExist($model)
        { 
            switch ($model->id_type_accounting_document){
                case '1':
                    // return self::model()->find("id_carrier=:idCarrier and doc_number=:doc_number and id_type_accounting_document=:tipo and from_date=:from_date and to_date=:to_date",array(":idCarrier"=>$model->id_carrier,":doc_number"=>$model->doc_number,":tipo"=>$model->id_type_accounting_document,":from_date"=>$model->from_date,":to_date"=>$model->to_date)); 
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
                    return self::model()->findBySql("Select * from accounting_document_temp where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and doc_number='$model->doc_number'");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and doc_number=:doc_number",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=doc_number"=>$model->doc_number));
                    break;
                case '8':
                    return self::model()->findBySql("Select * from accounting_document_temp where id_type_accounting_document={$model->id_type_accounting_document} and id_accounting_document={$model->id_accounting_document} and doc_number='$model->doc_number'");
//                    return self::model()->find("id_type_accounting_document=:tipo and id_accounting_document=:fact_number and doc_number=:doc_number",array(":=tipo"=>$model->id_type_accounting_document,":=fact_number"=>$model->id_accounting_document,":=doc_number"=>$model->doc_number));
                    break;
            }
        } 
        /**
         * busca los destinos supplier asignados al carrier
         */
                
        public static function getListCarriersAsignados_DestSup($idCarrier)
	{
            return CHtml::listData(DestinationSupplier::model()->findAll("id_carrier=:idCarrier",array(":idCarrier"=>$idCarrier)), 'id','name');
	}

        /**
         * calcula los dias y hora para registrar en facturas recibidas
         * @param type $EmailfechaRecepcion
         * @param type $dia
         * @return type
         */
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
        
        public static function getJSonParams($model,$valid)
        {
            if (isset($model->id_type_accounting_document))$params['id_type_accounting_document'] = $model->id_type_accounting_document;
            if (isset($model->id))$params['id'] = $model->id;
            if (isset($model->id_carrier))$params['carrier']=Carrier::getName($model->id_carrier);
            if (isset($model->carrier_groups))$params['group']=  CarrierGroups::getName($model->carrier_groups);
            if (isset($model->issue_date))$params['issue_date']=$model->issue_date;
            if (isset($model->sent_date))$params['sent_date']=$model->sent_date;
            if (isset($model->from_date))$params['from_date']=$model->from_date;
            if (isset($model->to_date))$params['to_date']=$model->to_date;
            if (isset($model->email_received_date))$params['email_received_date']=$model->email_received_date;
            if (isset($model->email_received_hour))$params['email_received_hour']=$model->email_received_hour;
            if (isset($model->valid_received_date))$params['valid_received_date']=$model->valid_received_date;
            if (isset($model->valid_received_hour))$params['valid_received_hour']=$model->valid_received_hour;
            if (isset($model->doc_number))$params['doc_number']=$model->doc_number;
            if (isset($model->id_accounting_document))$params['fact_number']=AccountingDocument::getDocNum($model->id_accounting_document);
            if (isset($model->minutes))$params['minutes'] =$model->minutes; 
            if (isset($model->amount))$params['amount'] =$model->amount; 
            if (isset($model->note))$params['note'] =$model->note; 
            if (isset($model->min_etx))$params['min_etx'] =$model->min_etx; 
            if (isset($model->min_carrier))$params['min_carrier'] =$model->min_carrier;
            if (isset($model->rate_etx))$params['rate_etx'] =$model->rate_etx;
            if (isset($model->rate_carrier))$params['rate_carrier'] =$model->rate_carrier;
            if (isset($model->id_destination_supplier))$params['destinationSupp'] =DestinationSupplier::getName($model->id_destination_supplier);
            if (isset($model->id_destination))$params['destination'] =Destination::getName($model->id_destination);
            if (isset($model->id_currency))$params['currency'] =  Currency::getName($model->id_currency);
            $params['valid'] = $valid;
            return $params;
        }
        
        public static function resolvedDateHour($model)
        {
            $dia = date("N", strtotime($model->email_received_date));
            if ($dia == 1 || $dia == 2) {

                if ($model->email_received_hour >= '08:00' && $model->email_received_hour <= '17:00') 
                {   
                    $model->valid_received_date = $model->email_received_date;
                    $model->valid_received_hour = $model->email_received_hour;
                    
                } else {
                    if($model->email_received_hour < '08:00'){
                        $model->valid_received_date = $model->email_received_date;
                    }else{
                        $model->valid_received_date = self::model()->getValidDate($model->email_received_date, $dia);
                    }
                    $model->valid_received_hour = '08:00';
                }
            } else {
                $model->valid_received_date = self::model()->getValidDate($model->email_received_date, $dia);
                $model->valid_received_hour = '08:00';
            }
            return $model;
        }
        
        public static function setValues($model){
            
            switch ($model->id_type_accounting_document){
                case 1:
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->valid_received_hour=NULL;
                    $model->sent_date=$model->issue_date;
                    $model->id_accounting_document=NULL;
                    $model->carrier_groups=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->id_destination=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->amount = Utility::ComaPorPunto($model->amount);
                    $model->minutes = Utility::ComaPorPunto($model->minutes);
                    $model->note=Utility::snull($model->note);
                    $model->confirm=0;
                    break;
                case 2:
                    $model->sent_date=NULL;
                    $model->id_accounting_document=NULL;
                    $model->carrier_groups=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->id_destination=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->amount = Utility::ComaPorPunto($model->amount);
                    $model->minutes = Utility::ComaPorPunto($model->minutes);
                    $model->note=Utility::snull($model->note);
                    $model->confirm=1;
                    $model = self::model()->resolvedDateHour($model);
                    break;
                case 3:
                    $model->from_date=NULL;
                    $model->to_date=NULL;
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->valid_received_hour=NULL;
                    $model->sent_date=$model->issue_date;
                    $model->id_accounting_document=NULL;
                    $model->minutes=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->id_destination=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->amount=Utility::ComaPorPunto($model->amount);
                    $model->note=Utility::snull($model->note);
                    $model->id_carrier=Carrier::getCarrierLeader($model->carrier_groups);
                    $model->confirm=1;
                    break;
                case 4:
                    $model->issue_date=$model->valid_received_date;
                    $model->from_date=NULL;
                    $model->to_date=NULL;
                    $model->email_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->valid_received_hour=NULL;
                    $model->sent_date=NULL;
                    $model->id_accounting_document=NULL;
                    $model->minutes=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->id_destination=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->amount = Utility::ComaPorPunto($model->amount);
                    $model->note=Utility::snull($model->note);
                    $model->id_carrier=Carrier::getCarrierLeader($model->carrier_groups);
                    $model->confirm=1;
                    break;
                case 5:
                    $model->issue_date=date("Y-m-d");
                    $model->carrier_groups=NULL;
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->doc_number=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->minutes=NULL;
                    $model->rate_etx=Utility::ComaPorPunto($model->rate_etx);;
                    $model->rate_carrier=Utility::ComaPorPunto($model->rate_carrier);;
                    $model->amount=Utility::ComaPorPunto(($model->rate_etx * $model->min_etx)-($model->rate_carrier * $model->min_carrier));
                    $model->note=Utility::snull($model->note);
                    $model->confirm=1;
                    $model->id_currency=AccountingDocument::getBuscaMoneda($model->id_accounting_document);
                    break;
                case 6:
                    $model->issue_date=date("Y-m-d");
                    $model->carrier_groups=NULL;
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->doc_number=NULL;
                    $model->id_destination=NULL;
                    $model->minutes=NULL;
                    $model->rate_etx=Utility::ComaPorPunto($model->rate_etx);;
                    $model->rate_carrier=Utility::ComaPorPunto($model->rate_carrier);;
                    $model->amount=Utility::ComaPorPunto(($model->rate_etx * $model->min_etx)-($model->rate_carrier * $model->min_carrier));
                    $model->note=Utility::snull($model->note);
                    $model->confirm=1;
                    $model->id_currency=AccountingDocument::getBuscaMoneda($model->id_accounting_document);
                    $model->id_destination_supplier=DestinationSupplier::resolvedId($model->select_dest_supplier,$model->input_dest_supplier,$model->id_carrier);
                    break;
                case 7:
//                    $model->issue_date=date("Y-m-d");
                    $model->carrier_groups=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->minutes=NULL;
                    $model->amount = Utility::ComaPorPunto($model->amount);
                    $model->note=Utility::snull($model->note);
                    $model->confirm=1;
                    $model->id_currency=AccountingDocument::getBuscaMoneda($model->id_accounting_document);
                    break;
                case 8:
//                    $model->issue_date=date("Y-m-d");
                    $model->carrier_groups=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_date=NULL;
                    $model->valid_received_date=NULL;
                    $model->email_received_hour=NULL;
                    $model->min_etx=NULL;
                    $model->min_carrier=NULL;
                    $model->rate_etx=NULL;
                    $model->rate_carrier=NULL;
                    $model->id_destination=NULL;
                    $model->id_destination_supplier=NULL;
                    $model->select_dest_supplier=NULL;
                    $model->input_dest_supplier=NULL;
                    $model->minutes=NULL;
                    $model->amount = Utility::ComaPorPunto($model->amount);
                    $model->note=Utility::snull($model->note);
                    $model->confirm=1;
                    $model->id_currency=AccountingDocument::getBuscaMoneda($model->id_accounting_document);
                    break;
            }
            return $model;
        }
        
}