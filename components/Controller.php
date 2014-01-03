<?php
class Controller extends CController
{
	public $layout; // setting layout.

	public $indexModel;

	public $menu = array();

	public $breadcrumbs = array();

	public $pageSize = 10;
	
	public $modelName; // setting controller model name.

	public $model;

	public $baseUrl;

	public $pagerParams = array();

	public function init()
	{
		$this->baseUrl = empty(Yii::app()->theme) ? Yii::app()->request->baseUrl : Yii::app()->theme->baseUrl;
		$this->modelName = empty($this->modelName) ? str_replace('Controller', '', get_class($this)) : $this->modelName;
		if ($this->pageUrl) {
			$page = Yii::app()->request->getParam('page');
			$sort = Yii::app()->request->getParam('sort');
			if (!empty($page)) TbArray::defaultValue('page', $page, $this->pagerParams);
			if (!empty($sort)) TbArray::defaultValue('sort', $sort, $this->pagerParams);
		}
		return parent::init();
	}

	public function loadFilterModel()
	{
		$model = new $this->modelName('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET[$this->modelName])) {
			$model->attributes = $_GET[$this->modelName];
		}
		return $model;
	}

	public function loadModel($modelName = '', $id = '')
	{
		$model = NULL;

		$modelName = empty($modelName) ? $this->modelName : $modelName;
		$id = empty($id) ? $_GET['id'] : $id;

		if (!empty($id)) {
			$model = call_user_func($modelName.'::model')->findbyPk($id);
		}
		if($model === null) {
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
		return $model;
	}

	public function getModelID()
	{
		if (isset($_GET['id'])) {
			return $_GET['id'];
		} else {
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}

	/* actions setting area */
	
	public $crudActions = array('index', 'create', 'view', 'update', 'delete');

	public $crudActionsPath = array(
		'index'		=> 'andystrap.actions.crud.IndexAction',
		'create'	=> 'andystrap.actions.crud.CreateAction',
		'view'		=> 'andystrap.actions.crud.ViewAction',
		'update'	=> 'andystrap.actions.crud.UpdateAction',
		'delete'	=> 'andystrap.actions.crud.DeleteAction',
	);

	public $extraActions = array();

	public $extraActionsPath = array();

	public function actions()
	{
		$actions = array();
		foreach ($this->crudActions as $value) {
			if (array_key_exists($value, $this->crudActionsPath))
				$actions[$value] = $this->crudActionsPath[$value];
		}
		if (!empty($this->extraActionsPath)) {
			$actions = CMap::mergeArray($actions, $this->extraActionsPath);
		}
		return $actions;
	}

	public $pageUrl = FALSE;

	public function redirect($url, $terminate = true, $statusCode = 302)
	{
		if ($this->pageUrl) $url = AdHtml::urlPager($url);
		return parent::redirect($url, $terminate, $statusCode);
	}
}