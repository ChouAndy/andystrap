<?php

class AdHtml extends TbHtml
{
	public static function sidebar($items, $htmlOptions = array())
	{
		$output = self::openTag('div', array('class' => 'sidebar'));

		foreach ($items as $itemOptions) {
			// button title
			$color = TbArray::getValue('color', $htmlOptions, self::BUTTON_COLOR_PRIMARY);
			$activeColor = TbArray::getValue('activeColor', $htmlOptions, self::BUTTON_COLOR_WARNING);
			$htmlOptions['button'] = array(
				'block' => TRUE,
			);

			TbArray::defaultValue('color', $color, $htmlOptions['button']);
			if (isset($itemOptions['color'])) {
				$htmlOptions['button']['color'] = $itemOptions['color'];
			}

			// active items color setting
			if (isset($itemOptions['controller'])) {
				if (is_array($itemOptions['controller'])) {
					foreach ($itemOptions['controller'] as $value) {
						if ($value == Yii::app()->controller->id) {
							$htmlOptions['button']['color'] = $activeColor;
						}
					}
				} else if ($itemOptions['controller'] == Yii::app()->controller->id) {
					$htmlOptions['button']['color'] = $activeColor;
				}
			}
			if (isset($itemOptions['action'])) {
				if (is_array($itemOptions['action'])) {
					foreach ($itemOptions['action'] as $value) {
						if ($value == Yii::app()->controller->action->id) {
							$htmlOptions['button']['color'] = $activeColor;
						}
					}
				} else if ($itemOptions['action'] == Yii::app()->controller->action->id) {
					$htmlOptions['button']['color'] = $activeColor;
				}
			}

			// build header - use link or not
			if (isset($itemOptions['url'])) {
				TbArray::defaultValue('url', $itemOptions['url'], $htmlOptions['button']);
				$output .= self::linkButton($itemOptions['label'], $htmlOptions['button']);
			} else {
				TbArray::defaultValue('data-toggle', 'collapse', $htmlOptions['button']);
				TbArray::defaultValue('data-target', '#'.$itemOptions['name'], $htmlOptions['button']);
				$output .= self::button($itemOptions['label'], $htmlOptions['button']);
			}
			
			// subitems nav list
			if (isset($itemOptions['subitems'])) {
				// collapse setting
				$collapse = '';
				if (isset($itemOptions['collapse']) && $itemOptions['collapse']) {
					$collapse = ' in';
				}
				if (isset($itemOptions['controller'])) {
					if (is_array($itemOptions['controller'])) {
						foreach ($itemOptions['controller'] as $value) {
							if ($value == Yii::app()->controller->id) {
								$collapse = ' in';
							}
						}
					} else if ($itemOptions['controller'] == Yii::app()->controller->id) {
						$collapse = ' in';
					}
				}

				$output .= self::openTag('div', array('class' => 'accordion-group'));
				$output .= self::openTag('div', array('class' => 'collapse'.$collapse, 'id' => $itemOptions['name']));
				$output .= self::navList($itemOptions['subitems'], array('class' => 'sidenav'));
				$output .= self::closeTag('div');
				$output .= self::closeTag('div');
			}
		}
		$output .= self::closeTag('div');

		return $output;
	}

	public static function actionButtonsControlGroup(array $buttons)
	{
		$groupOptions = array();
		self::addCssClass('control-group', $groupOptions);

		$input = '';
		for ($i = 0; $i < count($buttons); $i++) {
			$input .= self::btn($buttons[$i]['type'], $buttons[$i]['label'], $buttons[$i]['htmlOptions']);
			if ($i < count($buttons) - 1) {
				$input .= ' ';
			}
		}

		$output = self::openTag('div', $groupOptions);
		$output .= self::controls($input);
		$output .= '</div>';

		return $output;
	}

	public static function actionButtons(array $buttons)
	{
		$areaOptions = array();
		self::addCssClass('action_area', $areaOptions);

		$input = '';
		for ($i = 0; $i < count($buttons); $i++) {
			$input .= self::btn($buttons[$i]['type'], $buttons[$i]['label'], $buttons[$i]['htmlOptions']);
			if ($i < count($buttons) - 1) {
				$input .= ' ';
			}
		}

		$output = self::openTag('div', $areaOptions);
		$output .= $input;
		$output .= '</div>';

		return $output;
	}

	public static function areaBackButton($label = 'Back', $url = 'javascript:history.go(-1)')
	{
		$backButton = array(
			array(
				'type' => self::BUTTON_TYPE_LINK,
				'label' => $label,
				'htmlOptions' => array(
					'color' => self::BUTTON_COLOR_PRIMARY,
					'url' => $url,
				)
			),
		);

		return self::actionButtons($backButton);
	}

	public static function areaFormButtons($confirm, $cancel = '', $url = array('index'))
	{
		$formButtons[] = array(
			'type' => self::BUTTON_TYPE_SUBMIT,
			'label' => $confirm,
			'htmlOptions' => array(
				'color' => self::BUTTON_COLOR_PRIMARY,
			)
		);

		if (!empty($cancel)) {
			if (Yii::app()->controller->pageUrl) {
				if (!empty($url)) {
					$url = self::urlPager($url);
				}
			}
			$formButtons[] = array(
				'type' => self::BUTTON_TYPE_LINK,
				'label' => $cancel,
				'htmlOptions' => array(
					'url' => $url,
				)
			);
		}

		return self::actionButtonsControlGroup($formButtons);
	}

	public static function activeCKEditorControlGroup($model, $attribute, $htmlOptions = array())
	{
		$color = TbArray::popValue('color', $htmlOptions);
		$groupOptions = TbArray::popValue('groupOptions', $htmlOptions, array());
		$controlOptions = TbArray::popValue('controlOptions', $htmlOptions, array());
		$label = TbArray::popValue('label', $htmlOptions);
		$labelOptions = TbArray::popValue('labelOptions', $htmlOptions, array());

		if (isset($label) && $label !== false) {
			$labelOptions['label'] = $label;
		}

		$help = TbArray::popValue('help', $htmlOptions, '');
		$helpOptions = TbArray::popValue('helpOptions', $htmlOptions, array());
		if (!empty($help)) {
			$help = self::inputHelp($help, $helpOptions);
		}
		$error = TbArray::popValue('error', $htmlOptions, '');

		self::addCssClass('control-group', $groupOptions);
		if (!empty($color)) {
			self::addCssClass($color, $groupOptions);
		}
		self::addCssClass('control-label', $labelOptions);
		self::addCssClass('controls', $controlOptions);

		if (TbArray::popValue('row', $controlOptions, false)) {
			self::addCssClass('controls-row', $controlOptions);
		}

		$before = TbArray::popValue('before', $controlOptions, '');
		$after = TbArray::popValue('after', $controlOptions, '');

		echo self::openTag('div', $groupOptions);
		if ($label !== false) {
			echo parent::activeLabelEx($model, $attribute, $labelOptions);
		}
		echo self::openTag('div', $controlOptions);

		Yii::app()->controller->widget('andystrap.widgets.AdCKEditor', array(
			'model' => $model,
			'attribute' => $attribute,
			'editorOptions' => isset($options) ? $options : array(),
			'htmlOptions' => $htmlOptions,
		));

		echo $error.$help;

		echo '</div>';
		echo '</div>';
	}

	public static function activeDropDownListControlGroup($model, $attribute, $data = array(), $htmlOptions = array())
	{
		$type = self::INPUT_TYPE_DROPDOWNLIST;

		$color = TbArray::popValue('color', $htmlOptions);
		$groupOptions = TbArray::popValue('groupOptions', $htmlOptions, array());
		$controlOptions = TbArray::popValue('controlOptions', $htmlOptions, array());
		$label = TbArray::popValue('label', $htmlOptions);
		$labelOptions = TbArray::popValue('labelOptions', $htmlOptions, array());

		if (isset($label) && $label !== false) {
			$labelOptions['label'] = $label;
		}

		$help = TbArray::popValue('help', $htmlOptions, '');
		$helpOptions = TbArray::popValue('helpOptions', $htmlOptions, array());
		if (!empty($help)) {
			$help = self::helpBlock($help, $helpOptions);
		}
		$error = TbArray::popValue('error', $htmlOptions, '');

		$input = isset($htmlOptions['input'])
			? $htmlOptions['input']
			: self::createActiveInput($type, $model, $attribute, $htmlOptions, $data);

		self::addCssClass('control-group', $groupOptions);
		if (!empty($color)) {
			self::addCssClass($color, $groupOptions);
		}
		self::addCssClass('control-label', $labelOptions);
		$output = self::openTag('div', $groupOptions);
		if ($label !== false) {
			$output .= parent::activeLabelEx($model, $attribute, $labelOptions);
		}

		/* set controls content */
		self::addCssClass('controls', $controlOptions);
		if (TbArray::popValue('row', $controlOptions, false)) {
			self::addCssClass('controls-row', $controlOptions);
		}
		$before = TbArray::popValue('before', $controlOptions, '');
		$after = TbArray::popValue('after', $controlOptions, '');
		$after = !empty($after) ? Self::help($after) : '';
		$content = $before . $input . $after . $error . $help;
		$output .= self::tag('div', $controlOptions, $content);

		$output .= '</div>';
		return $output;
	}

	public static function activeTextFieldControlGroup($model, $attribute, $htmlOptions = array(), $data = array())
	{
		$type = self::INPUT_TYPE_TEXT;

		$color = TbArray::popValue('color', $htmlOptions);
		$groupOptions = TbArray::popValue('groupOptions', $htmlOptions, array());
		$controlOptions = TbArray::popValue('controlOptions', $htmlOptions, array());
		$label = TbArray::popValue('label', $htmlOptions);
		$labelOptions = TbArray::popValue('labelOptions', $htmlOptions, array());

		if (isset($label) && $label !== false) {
			$labelOptions['label'] = $label;
		}

		$help = TbArray::popValue('help', $htmlOptions, '');
		$helpOptions = TbArray::popValue('helpOptions', $htmlOptions, array());
		if (!empty($help)) {
			$help = self::inputHelp($help, $helpOptions);
		}
		$error = TbArray::popValue('error', $htmlOptions, '');

		$input = isset($htmlOptions['input'])
			? $htmlOptions['input']
			: self::createActiveInput($type, $model, $attribute, $htmlOptions, $data);

		self::addCssClass('control-group', $groupOptions);
		if (!empty($color)) {
			self::addCssClass($color, $groupOptions);
		}
		self::addCssClass('control-label', $labelOptions);
		$output = self::openTag('div', $groupOptions);
		if ($label !== false) {
			$output .= parent::activeLabelEx($model, $attribute, $labelOptions);
		}

		/* set controls content */
		self::addCssClass('controls', $controlOptions);
		if (TbArray::popValue('row', $controlOptions, false)) {
			self::addCssClass('controls-row', $controlOptions);
		}
		$before = TbArray::popValue('before', $controlOptions, '');
		$after = TbArray::popValue('after', $controlOptions, '');
		$after = !empty($after) ? Self::help($after) : '';
		$content = $before . $input . $after . $error . $help;
		$output .= self::tag('div', $controlOptions, $content);

		$output .= '</div>';
		return $output;
	}

	public static function activeTypeAheadControlGroup($model, $attribute, $source, $htmlOptions = array())
	{
		$color = TbArray::popValue('color', $htmlOptions);
		$groupOptions = TbArray::popValue('groupOptions', $htmlOptions, array());
		$controlOptions = TbArray::popValue('controlOptions', $htmlOptions, array());
		$label = TbArray::popValue('label', $htmlOptions);
		$labelOptions = TbArray::popValue('labelOptions', $htmlOptions, array());

		if (isset($label) && $label !== false) {
			$labelOptions['label'] = $label;
		}

		$help = TbArray::popValue('help', $htmlOptions, '');
		$helpOptions = TbArray::popValue('helpOptions', $htmlOptions, array());
		if (!empty($help)) {
			$help = self::inputHelp($help, $helpOptions);
		}
		$error = TbArray::popValue('error', $htmlOptions, '');

		self::addCssClass('control-group', $groupOptions);
		if (!empty($color)) {
			self::addCssClass($color, $groupOptions);
		}
		self::addCssClass('control-label', $labelOptions);
		self::addCssClass('controls', $controlOptions);

		if (TbArray::popValue('row', $controlOptions, false)) {
			self::addCssClass('controls-row', $controlOptions);
		}

		$before = TbArray::popValue('before', $controlOptions, '');
		$after = TbArray::popValue('after', $controlOptions, '');

		echo self::openTag('div', $groupOptions);
		if ($label !== false) {
			echo parent::activeLabelEx($model, $attribute, $labelOptions);
		}
		echo self::openTag('div', $controlOptions);

		Yii::app()->controller->widget('bootstrap.widgets.TbTypeAhead', array(
			'model' => $model,
			'attribute' => $attribute,
			'source' => $source,
			'htmlOptions' => $htmlOptions,
		));

		echo $error.$help;

		echo '</div>';
		echo '</div>';
	}

	public static function activeTimePickerControlGroup($model, $attribute, $htmlOptions = array())
	{
		$color = TbArray::popValue('color', $htmlOptions);
		$groupOptions = TbArray::popValue('groupOptions', $htmlOptions, array());
		$controlOptions = TbArray::popValue('controlOptions', $htmlOptions, array());
		$label = TbArray::popValue('label', $htmlOptions);
		$labelOptions = TbArray::popValue('labelOptions', $htmlOptions, array());

		if (isset($label) && $label !== false) {
			$labelOptions['label'] = $label;
		}

		$help = TbArray::popValue('help', $htmlOptions, '');
		$helpOptions = TbArray::popValue('helpOptions', $htmlOptions, array());
		if (!empty($help)) {
			$help = self::inputHelp($help, $helpOptions);
		}
		$error = TbArray::popValue('error', $htmlOptions, '');

		self::addCssClass('control-group', $groupOptions);
		if (!empty($color)) {
			self::addCssClass($color, $groupOptions);
		}
		self::addCssClass('control-label', $labelOptions);
		self::addCssClass('controls', $controlOptions);

		if (TbArray::popValue('row', $controlOptions, false)) {
			self::addCssClass('controls-row', $controlOptions);
		}

		$before = TbArray::popValue('before', $controlOptions, '');
		$after = TbArray::popValue('after', $controlOptions, '');

		echo self::openTag('div', $groupOptions);
		if ($label !== false) {
			echo parent::activeLabelEx($model, $attribute, $labelOptions);
		}
		echo self::openTag('div', $controlOptions);

		$pluginOptions = TbArray::popValue('pluginOptions', $htmlOptions, array());

		Yii::app()->controller->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
			'model' => $model,
			'attribute' => $attribute,
			'htmlOptions' => $htmlOptions,
			'pluginOptions' => $pluginOptions,
		));

		echo $error.$help;

		echo '</div>';
		echo '</div>';
	}

	public static function iconNav($icon, $htmlOptions = array(), $tagName = 'i')
	{
		if (is_string($icon)) {
			if (strpos($icon, 'nav') === false) {
				$icon = 'nav-' . implode(' nav-', explode(' ', $icon));
			}
			self::addCssClass($icon, $htmlOptions);
			return self::openTag($tagName, $htmlOptions) . parent::closeTag($tagName); // tag won't work in this case
		}
		return '';
	}

	public static function link($text, $url = '#', $htmlOptions = array())
	{
		$url = self::urlPager($url);
		return parent::link($text, $url, $htmlOptions);
	}

	public static function urlPager($url, $pageVar = 'page', $sortVar = 'sort')
	{
		$currentPage = Yii::app()->request->getParam($pageVar);
		if (!empty($currentPage)) {
			if (is_array($url)) {
				TbArray::defaultValue($pageVar, $currentPage, $url);
			} else {
				$url .= '&'.$pageVar.'='.$currentPage;
			}
		}
		$currentSort = Yii::app()->request->getParam($sortVar);
		if (!empty($currentSort)) {
			if (is_array($url)) {
				TbArray::defaultValue($sortVar, $currentSort, $url);
			} else {
				$url .= '&'.$sortVar.'='.$currentSort;
			}
		}
		return $url;
	}

	public static function buttonToolbar(array $groups, $htmlOptions = array())
	{
		if (!empty($groups)) {
			$urlPager = TbArray::popValue('urlPager', $htmlOptions);
			if ($urlPager) {
				$pageVar = TbArray::popValue('pageVar', $htmlOptions, 'page');
				foreach ($groups as $i => $groupOptions) {
					$items = TbArray::popValue('items', $groupOptions, array());
					if (!empty($items)) {
						foreach ($groups[$i]['items'] as $j => $itemsOptions) {
							$url = TbArray::popValue('url', $groups[$i]['items'][$j]);
							if (!empty($url)) {
								TbArray::defaultValue('url', self::urlPager($url, $pageVar), $groups[$i]['items'][$j]);
							}
							$submit = TbArray::popValue('submit', $groups[$i]['items'][$j]);
							if (!empty($submit)) {
								TbArray::defaultValue('submit', self::urlPager($submit, $pageVar), $groups[$i]['items'][$j]);
							}
						}
					}
				}
			}
		}
		return parent::buttonToolbar($groups, $htmlOptions);
	}

	public static function iconFA($icon, $htmlOptions = array(), $tagName = 'i')
	{
		if (is_string($icon)) {
			self::addCssClass('fa', $htmlOptions);

			$icon = 'fa-'.$icon;
			self::addCssClass($icon, $htmlOptions);
			
			$size = TbArray::popValue('size', $htmlOptions); // size setting
			if (!empty($size)) {
				if ($size >= 1 && $size <= 5) {
					$sizeClass = '';
					if ($size == 1) {
						$sizeClass = 'fa-lg';
					} else {
						$sizeClass = 'fa-'.$size.'x';
					}
					self::addCssClass($sizeClass, $htmlOptions);
				}
			}

			$fw = TbArray::popValue('fw', $htmlOptions); // fixed width setting
			if ($fw) self::addCssClass('fa-fw', $htmlOptions);

			$border = TbArray::popValue('border', $htmlOptions); // border setting
			if ($border) self::addCssClass('fa-border', $htmlOptions);

			$spin = TbArray::popValue('spin', $htmlOptions); // spin setting
			if ($spin) self::addCssClass('fa-spin', $htmlOptions);

			$rotate = TbArray::popValue('rotate', $htmlOptions); // rotate setting
			if (!empty($rotate)) {
				if ($rotate == 90 || $rotate == 180 || $rotate == 270) {
					$rotateClass = 'fa-rotate-'.$rotate;
					self::addCssClass($rotateClass, $htmlOptions);
				}
			}

			$flipH = TbArray::popValue('flipH', $htmlOptions); // flip horizontal setting
			if ($flipH) self::addCssClass('fa-flip-horizontal', $htmlOptions);

			$flipV = TbArray::popValue('flipV', $htmlOptions); // flip vertical setting
			if ($flipV) self::addCssClass('fa-flip-vertical', $htmlOptions);

			return self::openTag($tagName, $htmlOptions) . parent::closeTag($tagName); // tag won't work in this case
		}
		return '';
	}

	public static function nav($type, $items, $htmlOptions = array())
	{
		self::addCssClass('nav', $htmlOptions);
		if (!empty($type)) {
			self::addCssClass('nav-' . $type, $htmlOptions);
		}
		$stacked = TbArray::popValue('stacked', $htmlOptions, false);
		if ($type !== self::NAV_TYPE_LIST && $stacked) {
			self::addCssClass('nav-stacked', $htmlOptions);
		}
		return self::menu($items, $htmlOptions);
	}

	public static function menu(array $items, $htmlOptions = array(), $depth = 0)
	{
		// todo: consider making this method protected.
		if (!empty($items)) {
			$htmlOptions['role'] = 'menu';
			$output = self::openTag('ul', $htmlOptions);
			foreach ($items as $itemOptions) {
				if (is_string($itemOptions)) {
					$output .= $itemOptions;
				} else {
					if (isset($itemOptions['visible'])) {
						if (!TbArray::popValue('visible', $itemOptions)) continue;
					}

					// todo: consider removing the support for htmlOptions.
					$options = TbArray::popValue('htmlOptions', $itemOptions, array());
					if (!empty($options)) {
						$itemOptions = TbArray::merge($options, $itemOptions);
					}
					$label = TbArray::popValue('label', $itemOptions, '');
					if (TbArray::popValue('active', $itemOptions, false)) {
						self::addCssClass('active', $itemOptions);
					}
					if (TbArray::popValue('disabled', $itemOptions, false)) {
						self::addCssClass('disabled', $itemOptions);
					}
					if (!isset($itemOptions['linkOptions'])) {
						$itemOptions['linkOptions'] = array();
					}
					$icon = TbArray::popValue('icon', $itemOptions);
					if (!empty($icon)) {
						$label = self::iconFA($icon).' '.$label;
					}
					$items = TbArray::popValue('items', $itemOptions, array());
					$url = TbArray::popValue('url', $itemOptions, false);
					if (empty($items)) {
						if (!$url) {
							$output .= self::menuHeader($label);
						} else {
							$itemOptions['linkOptions']['tabindex'] = -1;
							$output .= self::menuLink($label, $url, $itemOptions);
						}
					} else {
						$output .= self::menuDropdown($label, $url, $items, $itemOptions, $depth);
					}
				}
			}
			$output .= '</ul>';
			return $output;
		} else {
			return '';
		}
	}
}