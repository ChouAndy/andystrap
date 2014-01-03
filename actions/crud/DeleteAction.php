<?php
class DeleteAction extends CAction
{
	public function run()
	{
		if (Yii::app()->request->isPostRequest) {
			$this->controller->loadModel()->delete();
			if(!isset($_POST['ajax'])) {
				$this->controller->redirect(array('/'.$this->controller->id));
			}
		} else {
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		}
	}
}