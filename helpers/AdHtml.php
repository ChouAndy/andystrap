<?php

class AdHtml extends TbHtml
{
	public static function sidebar($items, $subitems, $htmlOptions = array())
	{
		$output = self::openTag('div', array('class' => 'sidebar'));

		foreach ($items as $itemOptions) {
			// button title
			$htmlOptions['button'] = array(
				'color' => self::BUTTON_COLOR_PRIMARY,
				'block' => TRUE,
				'data-toggle' => 'collapse',
				'data-target' => '#'.$itemOptions['name']
			);
			$output .= self::button($itemOptions['label'], $htmlOptions['button']);
			// nav list
			$collapse = '';
			if (isset($itemOptions['collapse']) && $itemOptions['collapse'] == TRUE) {
				$collapse = ' in';
			}
			if (isset($itemOptions['controller']) && Yii::app()->controller->id == $itemOptions['controller']) {
				$collapse = ' in';
			}
			$output .= self::openTag('div', array('class' => 'accordion-group'));
			$output .= self::openTag('div', array('class' => 'collapse'.$collapse, 'id' => $itemOptions['name']));
			$output .= self::navList($subitems[$itemOptions['name']], array('class' => 'sidenav'));
			$output .= self::closeTag('div');
			$output .= self::closeTag('div');

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

	public static function areaFormButtons($confirm, $cancel = '', $url = 'index')
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
					'url' => array($url),
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
		$after = Self::help(TbArray::popValue('after', $controlOptions, ''));
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
		$after = Self::help(TbArray::popValue('after', $controlOptions, ''));
		$content = $before . $input . $after . $error . $help;
		$output .= self::tag('div', $controlOptions, $content);

		$output .= '</div>';
		return $output;
	}
}
