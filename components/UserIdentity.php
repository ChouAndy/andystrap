<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ERROR_STATUS_NOT_ACTIVE = 3;

	public $id; // Login User's id

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('username' => $this->username));
		if ($user === null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if (AdString::encrypting($this->password) !== $user->password) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} else if ($user->is_active == 0 && Yii::app()->user->loginNotActive == false) {
			$this->errorCode = self::ERROR_STATUS_NOT_ACTIVE;
		} else {
			$this->id = $user->id;
			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->id;
	}
}