<?php
Yii::import('bootstrap.behaviors.TbWidget');
Yii::import('bootstrap.widgets.TbTabs');

class AdTabs extends TbTabs
{
	/**
	* Widget's initialization method
	*/
	public function init()
	{
		$this->attachBehavior('TbWidget', new TbWidget);
		$this->copyId();
		TbArray::defaultValue('placement', $this->placement, $this->htmlOptions);
		$this->initEvents();
	}

	/**
	* Widget's run method
	*/
	public function run()
	{
		$this->tabs = $this->normalizeTabs($this->tabs);
		echo TbHtml::tabbable($this->type, $this->tabs, $this->htmlOptions);
		$this->registerClientScript();
	}

	/**
	* Normalizes the tab configuration.
	* @param array $tabs a reference to the tabs tab configuration.
	*/
	protected function normalizeTabs($tabs)
	{
		$controller = $this->getController();
		if (isset($controller)) {
			foreach ($tabs as &$tabOptions) {
				$items = TbArray::getValue('items', $tabOptions, array());
				if (!empty($items)) {
					$tabOptions['items'] = $this->normalizeTabs($items);
				} else {
					if (isset($tabOptions['view'])) {
						$view = TbArray::popValue('view', $tabOptions);
						$viewData = TbArray::popValue('viewData', $tabOptions);
						if ($controller->getViewFile($view) !== false) {
							$tabOptions['content'] = $controller->renderPartial($view, $viewData, true);
						}
					}
				}
			}
		}
		return $tabs;
	}
}