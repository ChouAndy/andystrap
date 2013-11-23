<?php

Yii::import('bootstrap.widgets.TbButtonColumn');

class AdButtonColumn extends TbButtonColumn
{
	public $extraButtons;

	public $useViewButton = TRUE;

	public $template = '';

	protected function initDefaultButtons()
	{
		parent::initDefaultButtons();

		$this->buttons['view']['label'] = Yii::t('AdminModule.admin', 'View');
		$this->buttons['update']['label'] = Yii::t('AdminModule.admin', 'Update');
		$this->buttons['delete']['label'] = Yii::t('AdminModule.admin', 'Delete');

		$this->htmlOptions = array(
			'class' => 'ad-button-column'
		);

		// add extra buttons to $buttons
		if (isset($this->extraButtons)) {
			foreach($this->extraButtons as $id => $buttonOptions) {
				TbArray::defaultValue($id, $buttonOptions, $this->buttons);
				$this->template .= '{'.$id.'}';
			}
		}

		if ($this->useViewButton) {
			$this->template .= '{view}';
		}
	}

	protected function renderButton($id, $button, $row, $data)
	{
		if (isset($button['visible']) && !$this->evaluateExpression(
			$button['visible'], array('row' => $row, 'data' => $data)))	{
			return;
		}

		$url = TbArray::popValue('url', $button, '#');
		if ($url !== '#') {
			$url = $this->evaluateExpression($url, array('data' => $data, 'row' => $row));
		}

		$label = TbArray::popValue('label', $button, $id);
		$options = TbArray::popValue('options', $button, array());
		$icon = TbArray::popValue('icon', $button, false);

		echo AdHtml::link(TbHtml::icon($icon).' '.$label, $url, $options);
	}
}
