<?php

class AdminWebUser extends CWebUser
{

	/**
	* @var boolean whether to enable cookie-based login. Defaults to false.
	*/
	public $allowAutoLogin = TRUE;

	/**
	 * @var int
	 * @desc Remember Me Time (seconds), defalt = 3600*24*30 = 2592000 (30 days)
	 */
	public $rememberMeTime = 2592000; // 30 days

	public $loginUrl = array('/user/login');

	public $logoutUrl = array('/user/logout');

	public $returnUrl = array('/');

	public $returnLogoutUrl = array('/user/login');

	public $loginNotActive = FALSE;

	protected function afterLogin($fromCookie)
	{
		parent::afterLogin($fromCookie);
		$this->updateSession();
	}

	public function updateSession()
	{
		$user = $this->getUser($this->id);
		$userAttributes = array(
			'username' => $user->username,
			'role_id' => $user->role_id,
			'roles' => $user->role->name,
			'role_alias' => $user->role->alias,
			'last_login' => $user->last_login,
			'created' => $user->created,
		);
		foreach ($userAttributes as $name => $value) {
			$this->setState($name, $value);
		}
	}

	public function getUser($id = 0)
	{
		if (!$id && !Yii::app()->user->isGuest) {
			$id = Yii::app()->user->id;
		}
		if ($id) {
			return User::model()->findbyPk($id);
		} else {
			return false;
		}
	}

	public function getUserByName($username)
	{
		return User::model()->findByAttributes(array('username' => $username));
	}

	public function getAdmins()
	{
		$admins = User::model()->is_active()->is_admin()->findAll();
		$return_name = array();
		foreach ($admins as $admin) {
			array_push($return_name, $admin->username);
		}
		return $return_name;
	}

	public function isAdmin()
	{
		if (Yii::app()->user->isGuest) {
			return false;
		} else {
			if($this->getUser()->role->name == 'admin') {
				return true;
			} else {
				return false;
			}
		}
	}

	public function checkAccess($operation, $params=array())
	{
		if (empty($this->id)) {
		// Not identified => no rights
			return false;
		}
		$role = $this->getState("roles");
		if ($role === 'admin') {
			return true; // admin role has access to everything
		}
		// allow access if the operation request is the current user's role
		return ($operation === $role);
	}
}