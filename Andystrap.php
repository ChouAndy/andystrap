<?php

class Andystrap extends CApplicationComponent
{
	public $_assetsUrl;

	/**
	 * @var int static counter, used for determining script identifiers
	 */
	public static $counter = 0;

	/**
	 * @var bool|null Whether to republish assets on each request.
	 * If set to true, all YiiBooster assets will be republished on each request.
	 * Passing null to this option restores the default handling of CAssetManager of YiiBooster assets.
	 *
	 * @since YiiBooster 1.0.6
	 */
	public $forceCopyAssets = false;

	public $loadFontAwesome = TRUE;

	public $loadBootstrap = TRUE;

	public $loadBootstrapResponsive	= TRUE;

	public $loadSidebar = TRUE;

	public function init()
	{
		/* Packages */
		$packages = require('packages.php');
		foreach ($packages as $name => $definition) {
			Yii::app()->clientScript->addPackage($name, $definition);
		}
		/* load css - core */
		$this->registerCssCore();
		/* load script - ExternalLink */
		$this->registerScriptExternalLink();
		parent::init();
	}

	public function registerCssCore()
	{
		if ($this->loadBootstrap) {
			Yii::app()->bootstrap->registerCoreCss();
			Yii::app()->bootstrap->registerYiistrapCss();
			Yii::app()->bootstrap->registerAllScripts();
			if ($this->loadBootstrapResponsive) Yii::app()->bootstrap->registerResponsiveCss();
			$this->registerCssBootstrapFix();
		}
		if ($this->loadFontAwesome) $this->registerCssFontAwesome();
		if ($this->loadSidebar) $this->registerCssSidebar();
	}

	public function registerCssBootstrapFix($url = null)
	{
		if ($url === null) {
			$fileName = 'bootstrap-fix.css';
			$url = $this->getAssetsUrl().'/bootstrap-fix/css/'.$fileName;
		}
		Yii::app()->clientScript->registerCssFile($url);
	}

	public function registerCssSidebar($url = null)
	{
		if ($url === null) {
			$fileName = 'sidebar.css';
			$url = $this->getAssetsUrl().'/common/css/'.$fileName;
		}
		Yii::app()->clientScript->registerCssFile($url);
	}

	public function registerCssFontAwesome($url = null)
	{
		if ($url === null) {
			$fileName = 'font-awesome.min.css';
			$url = $this->getAssetsUrl().'/font-awesome/css/'.$fileName;
		}
		Yii::app()->clientScript->registerCssFile($url);
	}

	public function registerScriptExternalLink($url = null)
	{
		// link [rel=external] open with anothor window
		Yii::app()->clientScript->registerScript(
			__CLASS__.'#Script'.self::$counter++,
			"jQuery('a[rel=external]').click(function(){window.open(this.href);return false;});",
			CClientScript::POS_END
		);
	}

	/**
	 * common functions
	 */
	public function getAssetsUrl()
	{
		if (isset($this->_assetsUrl)) {
			return $this->_assetsUrl;
		} else {
			return $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
				Yii::getPathOfAlias('andystrap.assets'),
				false,
				-1,
				$this->forceCopyAssets
			);
		}
	}

	public function registerPackage($name)
	{
		return Yii::app()->clientScript->registerPackage($name);
	}

	public function t($message, $params=array ())
	{
		return Yii::t('andystrap.widget', $message, $params);
	}
}
