<?php

class Andystrap extends CApplicationComponent
{

	/**
	 * @var CClientScript Something which can register assets for later inclusion on page.
	 * For now it's just the `Yii::app()->clientScript`
	 */
	public $assetsRegistry;

	/**
	 * @var string handles the assets folder path.
	 */
	public $_assetsUrl;

	/**
	 * @var bool|null Whether to republish assets on each request.
	 * If set to true, all YiiBooster assets will be republished on each request.
	 * Passing null to this option restores the default handling of CAssetManager of YiiBooster assets.
	 *
	 * @since YiiBooster 1.0.6
	 */
	public $forceCopyAssets = false;

	public function init()
	{
		/* assetsRegistry */
		if (!$this->assetsRegistry) {
			$this->assetsRegistry = Yii::app()->getClientScript();
		}

		/* Packages */
		$packages = require('components/packages.php');
		foreach ($packages as $name => $definition) {
			$this->assetsRegistry->addPackage($name, $definition);
		}

		parent::init();
	}

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
		return $this->assetsRegistry->registerPackage($name);
	}

	public function t($message, $params=array ())
	{
		return Yii::t('andystrap.widget', $message, $params);
	}
}
