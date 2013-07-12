<?php

/**
 * This is the model class for table "rrhistory".
 *
 * The followings are the available columns in table 'rrhistory':
 * @property integer $id
 * @property string $date_balance
 * @property double $minutes
 * @property double $acd
 * @property double $asr
 * @property double $margin_percentage
 * @property double $margin_per_minute
 * @property double $cost_per_minute
 * @property double $revenue_per_min
 * @property double $pdd
 * @property double $incomplete_calls
 * @property double $incomplete_calls_ner
 * @property double $complete_calls
 * @property double $complete_calls_ner
 * @property double $calls_attempts
 * @property double $duration_real
 * @property double $duration_cost
 * @property double $ner02_efficient
 * @property double $ner02_seizure
 * @property double $pdd_calls
 * @property double $revenue
 * @property double $cost
 * @property double $margin
 * @property string $date_change
 * @property integer $type
 * @property integer $id_balance
 * @property integer $id_carrier
 * @property integer $id_destination
 * @property integer $id_destination_int
 *
 * The followings are the available model relations:
 * @property Balance $idBalance
 */
class Rrhistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rrhistory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, type', 'required'),
			array('type, id_balance, id_carrier, id_destination, id_destination_int', 'numerical', 'integerOnly'=>true),
			array('minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin', 'numerical'),
			array('date_change', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_balance, id_carrier, id_destination, id_destination_int', 'safe', 'on'=>'search'),
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
			'idBalance' => array(self::BELONGS_TO, 'Balance', 'id_balance'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_balance' => 'Date Balance',
			'minutes' => 'Minutes',
			'acd' => 'Acd',
			'asr' => 'Asr',
			'margin_percentage' => 'Margin Percentage',
			'margin_per_minute' => 'Margin Per Minute',
			'cost_per_minute' => 'Cost Per Minute',
			'revenue_per_min' => 'Revenue Per Min',
			'pdd' => 'Pdd',
			'incomplete_calls' => 'Incomplete Calls',
			'incomplete_calls_ner' => 'Incomplete Calls Ner',
			'complete_calls' => 'Complete Calls',
			'complete_calls_ner' => 'Complete Calls Ner',
			'calls_attempts' => 'Calls Attempts',
			'duration_real' => 'Duration Real',
			'duration_cost' => 'Duration Cost',
			'ner02_efficient' => 'Ner02 Efficient',
			'ner02_seizure' => 'Ner02 Seizure',
			'pdd_calls' => 'Pdd Calls',
			'revenue' => 'Revenue',
			'cost' => 'Cost',
			'margin' => 'Margin',
			'date_change' => 'Date Change',
			'type' => 'Type',
			'id_balance' => 'Id Balance',
			'id_carrier' => 'Id Carrier',
			'id_destination' => 'Id Destination',
			'id_destination_int' => 'Id Destination Int',
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
		$criteria->compare('date_balance',$this->date_balance,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('acd',$this->acd);
		$criteria->compare('asr',$this->asr);
		$criteria->compare('margin_percentage',$this->margin_percentage);
		$criteria->compare('margin_per_minute',$this->margin_per_minute);
		$criteria->compare('cost_per_minute',$this->cost_per_minute);
		$criteria->compare('revenue_per_min',$this->revenue_per_min);
		$criteria->compare('pdd',$this->pdd);
		$criteria->compare('incomplete_calls',$this->incomplete_calls);
		$criteria->compare('incomplete_calls_ner',$this->incomplete_calls_ner);
		$criteria->compare('complete_calls',$this->complete_calls);
		$criteria->compare('complete_calls_ner',$this->complete_calls_ner);
		$criteria->compare('calls_attempts',$this->calls_attempts);
		$criteria->compare('duration_real',$this->duration_real);
		$criteria->compare('duration_cost',$this->duration_cost);
		$criteria->compare('ner02_efficient',$this->ner02_efficient);
		$criteria->compare('ner02_seizure',$this->ner02_seizure);
		$criteria->compare('pdd_calls',$this->pdd_calls);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('cost',$this->cost);
		$criteria->compare('margin',$this->margin);
		$criteria->compare('date_change',$this->date_change,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('id_balance',$this->id_balance);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_destination',$this->id_destination);
		$criteria->compare('id_destination_int',$this->id_destination_int);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rrhistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
