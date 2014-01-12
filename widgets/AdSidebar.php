<?php
Yii::import('andystrap.helpers.AdHtml');

class AdSidebar extends CWidget
{
	public $noactiveColor = AdHtml::BUTTON_COLOR_PRIMARY; // 未作用顏色

	public $activeColor = AdHtml::BUTTON_COLOR_WARNING; // 作用中顏色

	public $useIcon = TRUE;

	public $defaultIcon = 'cog';

	public $items;

	// function to init the widget
	public function init()
	{
		
	}

	// function to run the widget
    public function run()
    {
		$output = AdHtml::openTag('div', array('class' => 'andystrap-sidebar'));

		foreach ($this->items as $item) {
			$item['htmlOptions'] = array();
			TbArray::defaultValue('block', TRUE, $item['htmlOptions']);
			TbArray::defaultValue('color', $this->noactiveColor, $item['htmlOptions']);
			/* 載入給定按鈕顏色 */
			if (isset($item['color'])) {
				$item['htmlOptions']['color'] = TbArray::popValue('color', $item);
			}
			/* 載入給定作用的 controller */
			if (isset($item['controller'])) {
				$controller = TbArray::popValue('controller', $item);
				if (is_array($controller)) {
					foreach ($controller as $value) {
						if ($value == Yii::app()->controller->id) {
							$item['htmlOptions']['color'] = $this->activeColor;
						}
					}
				} else if ($controller == Yii::app()->controller->id) {
					$item['htmlOptions']['color'] = $this->activeColor;
				}
			}
			if (isset($item['action'])) {
				$action = TbArray::popValue('action', $item);
				if (is_array($action)) {
					foreach ($action as $value) {
						if ($value == Yii::app()->controller->action->id) {
							$item['htmlOptions']['color'] = $this->activeColor;
						}
					}
				} else if ($action == Yii::app()->controller->action->id) {
					$item['htmlOptions']['color'] = $this->activeColor;
				}
			}

			if ($this->useIcon) {
				$icon = $this->defaultIcon;
				if (isset($item['icon'])) {
					$icon = TbArray::popValue('icon', $item);
				}
				$item['label'] = AdHtml::iconFA($icon, array('fw' => TRUE)).' '.$item['label'];
			}

			// build header - use link or not
			if (isset($item['url'])) {
				$url = TbArray::popValue('url', $item);
				TbArray::defaultValue('url', $url, $item['htmlOptions']);
				$output .= AdHtml::linkButton($item['label'], $item['htmlOptions']);
			} else {
				TbArray::defaultValue('data-toggle', 'collapse', $item['htmlOptions']);
				TbArray::defaultValue('data-target', '#'.$item['name'], $item['htmlOptions']);
				$output .= AdHtml::button($item['label'], $item['htmlOptions']);
			}
			
			// subitems nav list
			if (isset($item['items'])) {
				$subitems = TbArray::popValue('items', $item);
				// collapse setting
				$collapse = '';
				if (isset($item['collapse']) && $item['collapse']) {
					TbArray::popValue('collapse', $item);
					$collapse = ' in';
				}
				if (isset($controller)) {
					if (is_array($controller)) {
						foreach ($controller as $value) {
							if ($value == Yii::app()->controller->id) {
								$collapse = ' in';
							}
						}
					} else if ($controller == Yii::app()->controller->id) {
						$collapse = ' in';
					}
				}

				$output .= AdHtml::openTag('div', array('class' => 'accordion-group'));
				$output .= AdHtml::openTag('div', array('class' => 'collapse'.$collapse, 'id' => $item['name']));
				$output .= AdHtml::navList($subitems, array('class' => 'sidenav'));
				$output .= AdHtml::closeTag('div');
				$output .= AdHtml::closeTag('div');
			}
		}
		$output .= AdHtml::closeTag('div');

		echo $output;
	}
}