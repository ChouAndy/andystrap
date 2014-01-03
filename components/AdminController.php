<?php
class AdminController extends Controller
{
	public $layout = '//layouts/columns/admin'; // setting module layout.

	public $indexModel = TRUE;

	public $pageUrl = TRUE;

	public function init()
	{
		$this->loadSidebarItems();
		parent::init();
	}

	public function filters()
	{
		return CMap::mergeArray(parent::filters(), array(
			'accessControl', // perform access control for CRUD operations
		));
	}

	public $roles = array('admin');

	public $extraAccessRules = array();
	
	public function accessRules()
	{
		$accessRules = array();

		/* extra access rules */
		if (!empty($this->extraAccessRules)) {
			foreach ($this->extraAccessRules as $value) {
				$accessRules[] = $value;
			}
		}

		/* CRUD access rules */
		$crudAccessRules = array('allow', // allow authenticated user
			'actions' => $this->crudActions,
			'roles' => $this->roles,
		);
		$accessRules[] = $crudAccessRules;

		/* extra actions access rules */
		if (!empty($this->extraActions)) {
			$extraActionsAccessRules = array('allow',
				'actions' => $this->extraActions,
				'roles' => $this->roles,
			);
			$accessRules[] = $extraActionsAccessRules;
		}

		/* deny access rules */
		$denyAccessRules = array('deny',  // deny all users
			'users'=>array('*'),
		);
		$accessRules[] = $denyAccessRules;

		return $accessRules;
	}

	public function loadSidebarItems()
	{
		if (empty(Yii::app()->params['sidebar'])) {
			$sidebar = require_once(Yii::getPathOfAlias('config.manual.sidebar').'.php');
			Yii::app()->params['sidebar'] = $sidebar;
		}
	}

	public $scenario;

	public $dataId;

	public function setScenario($model = '')
	{
		if (empty($model)) {
			$this->scenario = $this->model->scenario;
			if ($this->scenario == 'update') {
				$this->dataId = $this->model->id;
			}
		} else {
			$this->scenario = $model->scenario;
			if ($this->scenario == 'update') {
				$this->dataId = $model->id;
			}
		}
	}
}