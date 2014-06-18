<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	const ERROR_USERNAME_INVALID=1;
	const ERROR_PASSWORD_INVALID=2;
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
			$user=  UsersSine::model()->findByAttributes(array('email'=>$this->username));
		} else {
			$user=UsersSine::model()->findByAttributes(array('username'=>$this->username));
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
	public static function getEmail()
	{
		$usuario=UsersSine::model()->findByAttributes(array('username'=>Yii::app()->user->name));
		return $usuario->email;
	}
}