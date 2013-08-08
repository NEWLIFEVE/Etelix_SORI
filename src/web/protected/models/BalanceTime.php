<?php

/**
 * This is the model class for table "balance_time".
 *
 * The followings are the available columns in table 'balance_time':
 * @property integer $id
 * @property string $date_balance_time
 * @property integer $time
 * @property double $minutes
 * @property double $acd
 * @property double $asr
 * @property double $margin_percentage
 * @property double $margin_per_minute
 * @property double $cost_per_minute
 * @property double $revenue_per_minute
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
 * @property string $time_change
 * @property string $name_supplier
 * @property string $name_customer
 * @property string $name_destination
 */
class BalanceTime extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'balance_time';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_balance_time, time, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, time_change', 'required'),
			array('time', 'numerical', 'integerOnly'=>true),
			array('minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin', 'numerical'),
			array('name_supplier, name_customer, name_destination', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date_balance_time, time, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, time_change, name_supplier, name_customer, name_destination', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_balance_time' => 'Date Balance Time',
			'time' => 'Time',
			'minutes' => 'Minutes',
			'acd' => 'Acd',
			'asr' => 'Asr',
			'margin_percentage' => 'Margin Percentage',
			'margin_per_minute' => 'Margin Per Minute',
			'cost_per_minute' => 'Cost Per Minute',
			'revenue_per_minute' => 'Revenue Per Min',
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
			'time_change' => 'Time Change',
			'name_supplier' => 'Name Supplier',
			'name_customer' => 'Name Customer',
			'name_destination' => 'Name Destination',
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
		$criteria->compare('date_balance_time',$this->date_balance_time,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('acd',$this->acd);
		$criteria->compare('asr',$this->asr);
		$criteria->compare('margin_percentage',$this->margin_percentage);
		$criteria->compare('margin_per_minute',$this->margin_per_minute);
		$criteria->compare('cost_per_minute',$this->cost_per_minute);
		$criteria->compare('revenue_per_minute',$this->revenue_per_minute);
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
		$criteria->compare('time_change',$this->time_change,true);
		$criteria->compare('name_supplier',$this->name_supplier,true);
		$criteria->compare('name_customer',$this->name_customer,true);
		$criteria->compare('name_destination',$this->name_destination,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BalanceTime the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
