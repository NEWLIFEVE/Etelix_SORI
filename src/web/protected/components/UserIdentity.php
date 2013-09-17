<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $_type;
	const ERROR_EMAIL_INVALID=3;
	const ERROR_STATUS_INACTIV=4;
	/**
	 * Autentica un usuario.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		if (strpos($this->username,"@")) {
			$user=Users::model()->findByAttributes(array('email'=>$this->username));
		} else {
			$user=Users::model()->findByAttributes(array('username'=>$this->username));
		}
		if($user===null){
			if (strpos($this->username,"@")) {
				$this->errorCode=self::ERROR_EMAIL_INVALID;
			} else {
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
		}
		else if(UserHelp::encrypting($this->password)!==$user->password)
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else if($user->status!==true){
			$this->errorCode=self::ERROR_STATUS_INACTIV;
		}
		else 
		{
			$this->_id=$user->id;
			$this->_type=$user->id_type_of_user;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode;
	}

	/**
    * @return integer el ID del usuario autenticado
    */
	public function getId()
	{
		return $this->_id;
	}
	/**
	*
	*/
	public function getType()
	{
		return $this->_type;
	}
}