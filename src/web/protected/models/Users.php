<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property boolean $superuser
 * @property boolean $status
 * @property string $create_at
 * @property string $lastvisit_at
 * @property integer $id_type_of_user
 *
 * The followings are the available model relations:
 * @property Profiles[] $profiles
 * @property TypeOfUser $idTypeOfUser
 * @property CarrierManagers[] $carrierManagers
 */
class Users extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANNED=-1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('username, password, email, activkey, superuser, status, create_at, lastvisit_at', 'required'),
			array('id_type_of_user', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password, email, activkey', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, email, activkey, superuser, status, create_at, lastvisit_at, id_type_of_user', 'safe', 'on'=>'search'),
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

			'profiles' => array(self::HAS_MANY, 'Profiles', 'id_users'),
			'idTypeOfUser' => array(self::BELONGS_TO, 'TypeOfUser', 'id_type_of_user'),
			'carrierManagers' => array(self::HAS_MANY, 'CarrierManagers', 'id_users'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'activkey' => 'Activkey',
			'superuser' => 'Superuser',
			'status' => 'Status',
			'create_at' => 'Create At',
			'lastvisit_at' => 'Lastvisit At',
			'id_type_of_user' => 'Id Type Of User',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('activkey',$this->activkey,true);
		$criteria->compare('superuser',$this->superuser);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('lastvisit_at',$this->lastvisit_at,true);
		$criteria->compare('id_type_of_user',$this->id_type_of_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	* funcion que retorna un array con los nombres segun el tipo de usuario
	*/
	public static function usersByType($tipo)
	{
		$usuarios=self::model()->findAll('id_type_of_user=:tipo',array('tipo'=>$tipo));
		if($usuarios!=null)
		{
			foreach ($usuarios as $key => $usuario)
			{
				$arreglo[$key] = $usuario->username;
			}
		}
		else
		{
			$arreglo[0] = false;
		}
		return $arreglo;
	}
        
                
        public static function getName($id){           
            return self::model()->find("id=:id", array(':id'=>$id))->username;
        }
        /*
        * con el id de users me trae  email
        */
        public static function traeCorreo($id)
        {
           return self::model()->find("id =:id",array(":id"=>$id))->email;
        }
}
