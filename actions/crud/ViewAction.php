<?php
class ViewAction extends CAction
{
	public function run()
	{
		$this->controller->model = $this->controller->loadModel();
		$this->controller->render($this->id);
	}
}