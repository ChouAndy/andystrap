<?php
class AdFancyBox extends CWidget
{
	// @ string the id of the widget, since version 1.6
	public $id;
	// @ string the taget element on DOM
	public $target;
	// @ boolean whether to enable mouse plugin
	public $mouseEnabled = false;
	// @ boolean whether to enable button helper
	public $buttonEnabled = false;
	// @ boolean whether to enable media helper
	public $mediaEnabled = false;
	// @ boolean whether to enable thumbs helper
	public $thumbsEnabled = false;
	// @ array of config settings for fancybox
	public $config = array();
	
	// function to init the widget
	public function init()
	{
		// if not informed will generate Yii defaut generated id, since version 1.6
		if (!isset($this->id)) {
			$this->id = $this->getId();
		}
		// publish the required assets
		$this->publishAssets();
	}
	
	// function to run the widget
    public function run()
    {
		$config = CJavaScript::encode($this->config);
		Yii::app()->clientScript->registerScript(
			$this->getId(),
			"jQuery('$this->target').fancybox($config);",
			CClientScript::POS_END
		);
	}
	
	// function to publish and register assets on page 
	public function publishAssets()
	{
		Yii::app()->andystrap->registerPackage('fancybox');

		if ($this->mouseEnabled) {
			Yii::app()->andystrap->registerPackage('fancybox-mouseEnabled');
		}
		if ($this->buttonEnabled) {
			Yii::app()->andystrap->registerPackage('fancybox-buttonEnabled');
		}
		if ($this->mediaEnabled) {
			Yii::app()->andystrap->registerPackage('fancybox-mediaEnabled');
		}
		if ($this->thumbsEnabled) {
		Yii::app()->andystrap->registerPackage('fancybox-thumbsEnabled');
		}
	}
}