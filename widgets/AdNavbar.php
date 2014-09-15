<?php
class AdNavbar extends TbNavbar
{
	public $toplinks = array();

	public $sidelinks = array();

	public $sideVisible;

	public $collapse = TRUE;

	/* contents */
	public $headerContent;
	public $toplinksContent;
	public $sidelinksContent;

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		/* header content */
		ob_start();
		if ($this->brandLabel) {
			TbHtml::addCssClass('navbar-brand', $this->brandOptions);
			echo TbHtml::link($this->brandLabel, $this->brandUrl, $this->brandOptions);
		}
		$this->headerContent = ob_get_clean();

		/* sidelink content */
		if (!empty($this->sidelinks) && $this->sideVisible) {
			ob_start();
			foreach ($this->sidelinks as $item) {
				if (is_string($item)) {
					echo $item;
				} else {
					$widgetClassName = TbArray::popValue('class', $item);
					if ($widgetClassName !== null) {
						$this->controller->widget($widgetClassName, $item);
					}
				}
			}
			$this->sidelinksContent = ob_get_clean();

			if ($this->collapse) {
				TbHtml::addCssClass('sidelinks-collapse', $this->collapseOptions);
				ob_start();
				$collapseWidget = $this->controller->widget('bootstrap.widgets.TbCollapse',	array(
					'toggle' => false,
					'content' => $this->sidelinksContent,
					'htmlOptions' => $this->collapseOptions,
				));
				$this->sidelinksContent = ob_get_clean();

				ob_start();
				echo TbHtml::navbarCollapseLink('#' . $collapseWidget->getId());
				echo $this->headerContent;
				$this->headerContent = ob_get_clean();
			}

			ob_start();
			echo AdHtml::openTag('nav', array('class' => 'navbar-default navbar-fixed-side'));
			echo $this->sidelinksContent;
			echo AdHtml::closeTag('nav');
			$this->sidelinksContent = ob_get_clean();
		}

		ob_start();
		$headerOptions = TbArray::popValue('headerOptions', $this->htmlOptions, array());
		TbHtml::addCssClass('navbar-header', $headerOptions);
		echo TbHtml::openTag('div', $headerOptions);
		echo $this->headerContent;
		echo AdHtml::closeTag('div');
		$this->headerContent = ob_get_clean();

		ob_start();
		foreach ($this->toplinks as $item) {
			if (is_string($item)) {
				echo $item;
			} else {
				$widgetClassName = TbArray::popValue('class', $item);
				if ($widgetClassName !== null) {
					$itemOptions = array('class' => 'navbar-nav');
					TbHtml::addCssClass(TbArray::popValue('htmlOptions', $item, array()), $itemOptions);
					TbArray::defaultValue('htmlOptions', $itemOptions, $item);
					$this->controller->widget($widgetClassName, $item);
				}
			}
		}
		$this->toplinksContent = ob_get_clean();

		ob_start();
		echo $this->headerContent;
		echo $this->toplinksContent;
		$content = ob_get_clean();

		echo AdHtml::navbar($content, $this->htmlOptions);
		echo $this->sidelinksContent;
	}
}