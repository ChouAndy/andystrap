<?php
class IndexAction extends CAction
{
	public function run()
	{
		if ($this->controller->indexModel) {
			$this->controller->model = $this->controller->loadFilterModel();
		}
		$this->controller->render($this->id);
	}
}