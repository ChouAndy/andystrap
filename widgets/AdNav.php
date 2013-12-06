<?php
Yii::import('bootstrap.widgets.TbNav');

class AdNav extends TbNav
{
	public $activeController;

	public function run()
	{
		if (!empty($this->items)) {
			if ($this->type == 'buttonGroup') {
				foreach ($this->items as $key => $itemOptions)  {
					if (TbArray::popValue('active', $this->items[$key])) {
						TbHtml::addCssClass('active', $this->items[$key]['htmlOptions']);
						TbArray::defaultValue('color', TbHtml::BUTTON_COLOR_INVERSE, $this->items[$key]);
					}
				}
				echo TbHtml::buttonGroup($this->items, $this->htmlOptions);
			} else {
				echo TbHtml::nav($this->type, $this->items, $this->htmlOptions);
			}
		}
	}

	protected function isItemActive($item, $route)
	{
		$urlTrim = trim($item['url'][0], '/');
		$urlItems = explode('/', $urlTrim);

		/* when $item['url'][0] only has home url '/' */ 
		if ($item['url'][0] === '/') {
			$routeItems = explode('/', $route);
			if (count($routeItems) == 2 && $routeItems[0] == Yii::app()->defaultController) {
				$defaultController = ucfirst(Yii::app()->defaultController).'Controller';
				$defaultController = new $defaultController(Yii::app()->defaultController);
				if ($routeItems[1] == $defaultController->defaultAction) {
					unset($item['url']['#']);
					return true;
				}
			}
		}

		/* when $item['url'][0] only has /{controller} or /{module} or /{module}/{controller} */
		if (isset($item['url']) && is_array($item['url'])) {
			$routeItems = explode('/', $route);
			if (empty(Yii::app()->controller->module)) {
				if (count($urlItems == 1) && $urlTrim == $routeItems[0]) { // /{controller}
					unset($item['url']['#']);
					return true;
				}
				if (!empty($this->activeController[$urlTrim])) {
					foreach ($this->activeController[$urlTrim] as $value) {
						if ($value === $routeItems[0]) {
							unset($item['url']['#']);
							return true;
						}
					}
				}
			} else {
				if (count($urlItems) == 1 && $urlTrim == $routeItems[0]) { // /{module}
					unset($item['url']['#']);
					return true;
				}
				if (count($urlItems) == 2 && $urlItems[0] == $routeItems[0] &&  $urlItems[1] == $routeItems[1]) { // /{module}/{controller}
					unset($item['url']['#']);
					return true;
				}
			}
		}

		if (isset($item['url']) && is_array($item['url']) && !strcasecmp($urlTrim, $route)) {
			unset($item['url']['#']);
			if (count($item['url']) > 1) {
				foreach (array_splice($item['url'], 1) as $name => $value) {
					if (!isset($_GET[$name]) || $_GET[$name] != $value) {
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}
