<?php
Yii::import('bootstrap.widgets.TbNav');

class AdNav extends TbNav
{
	protected function isItemActive($item, $route)
	{
		/* when $item['url'][0] only has module id or controller id */ 
		$urlTrim = trim($item['url'][0], '/');
		$urlItems = explode('/', $urlTrim);

		if (isset($item['url']) && is_array($item['url']) && count($urlItems) == 1) {
			$routeItems = explode('/', $route);
			if (count($routeItems) == 3 && $routeItems[1] === 'default' && $routeItems[2] === 'index') {
				if ($urlTrim === $routeItems[0]) {
					unset($item['url']['#']);
					return true;
				}
			}
			if (count($routeItems) == 2 && $routeItems[1] === 'index') {
				if ($urlTrim === $routeItems[0]) {
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
