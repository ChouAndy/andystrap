<?php

class AdHtml extends TbHtml
{
	public static function sidebar($items, $htmlOptions = array())
	{
		$output = self::openTag('div', array('class' => 'sidebar'));

		foreach ($items as $itemOptions) {
			// button title
			$color = TbArray::getValue('color', $htmlOptions);
			$htmlOptions['button'] = array(
				'block' => TRUE,
			);
			if (!empty($color)) {
				TbArray::defaultValue('color', $color, $htmlOptions['button']);
			} else {
				TbArray::defaultValue('color', self::BUTTON_COLOR_PRIMARY, $htmlOptions['button']);
			}
			if (isset($itemOptions['color'])) {
				$htmlOptions['button']['color'] = $itemOptions['color'];
			}

			// active items color setting
			if (isset($itemOptions['controller'])) {
				if (is_array($itemOptions['controller'])) {
					foreach ($itemOptions['controller'] as $value) {
						if ($value == Yii::app()->controller->id) {
							$htmlOptions['button']['color'] = self::BUTTON_COLOR_WARNING;
						}
					}
				} else if ($itemOptions['controller'] == Yii::app()->controller->id) {
					$htmlOptions['button']['color'] = self::BUTTON_COLOR_WARNING;
				}
			}
			if (isset($itemOptions['action'])) {
				if (is_array($itemOptions['action'])) {
					foreach ($itemOptions['action'] as $value) {
						if ($value == Yii::app()->controller->action->id) {
							$htmlOptions['button']['color'] = self::BUTTON_COLOR_WARNING;
						}
					}
				} else if ($itemOptions['action'] == Yii::app()->controller->action->id) {
					$htmlOptions['button']['color'] = self::BUTTON_COLOR_WARNING;
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
}
