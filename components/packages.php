<?php

return array(

	'fancybox' => array(
		'depends' => array('jquery'),
		'baseUrl' => $this->getAssetsUrl() . '/fancybox/',
		'css' => array('css/jquery.fancybox.css'),
		'js' => array('js/jquery.fancybox.pack.js'),
	),

	'fancybox-mouseEnabled' => array(
		'baseUrl' => $this->getAssetsUrl() . '/fancybox/',
		'js' => array('js/jquery.mousewheel-3.0.6.pack.js'),
	),

	'fancybox-buttonEnabled' => array(
		'baseUrl' => $this->getAssetsUrl() . '/fancybox/',
		'css' => array('css/jquery.fancybox-buttons.css'),
		'js' => array('js/jquery.fancybox-buttons.js'),
	),

	'fancybox-mediaEnabled' => array(
		'baseUrl' => $this->getAssetsUrl() . '/fancybox/',
		'js' => array('js/jquery.fancybox-media.js'),
	),

	'fancybox-thumbsEnabled' => array(
		'baseUrl' => $this->getAssetsUrl() . '/fancybox/',
		'css' => array('css/jquery.fancybox-buttons.css'),
		'js' => array('js/jquery.fancybox-buttons.js'),
	),

	'ckeditor' => array(
		'baseUrl' => $this->getAssetsUrl() . '/ckeditor/',
		'js' => array('ckeditor.js')
	),

	'font-awesome' => array(
		'baseUrl' => $this->getAssetsUrl() . '/font-awesome/',
		'css' => array('css/font-awesome.min.css'),
	),

);
